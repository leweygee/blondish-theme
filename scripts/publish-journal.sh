#!/usr/bin/env bash
# =============================================================================
# NRG Zine Publishing Pipeline
# Publishes markdown content from content/journal/ to WordPress via WP-CLI
# =============================================================================

set -euo pipefail

CONTENT_DIR="content/journal"
LOG_FILE="pipeline.log"
WP_PATH="."  # Adjust to WordPress install path if different

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

log() {
    echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

warn() {
    echo -e "${YELLOW}[$(date '+%Y-%m-%d %H:%M:%S')] WARNING:${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[$(date '+%Y-%m-%d %H:%M:%S')] ERROR:${NC} $1" | tee -a "$LOG_FILE"
}

# -----------------------------------------------------------------------------
# Parse frontmatter from markdown file
# Returns value for a given key
# Usage: get_frontmatter "file.md" "slug"
# -----------------------------------------------------------------------------
get_frontmatter() {
    local file="$1"
    local key="$2"
    # Extract value between --- delimiters
    sed -n '/^---$/,/^---$/p' "$file" | grep "^${key}:" | sed "s/^${key}:[[:space:]]*//" | sed 's/^"//' | sed 's/"$//'
}

# -----------------------------------------------------------------------------
# Convert markdown body (after frontmatter) to HTML
# Requires: pandoc (brew install pandoc)
# -----------------------------------------------------------------------------
md_to_html() {
    local file="$1"
    # Skip frontmatter (everything between first two --- lines), then convert
    awk 'BEGIN{count=0} /^---$/{count++; next} count>=2{print}' "$file" | pandoc -f markdown -t html
}

# -----------------------------------------------------------------------------
# Create or update a WordPress post from a markdown file
# -----------------------------------------------------------------------------
publish_post() {
    local file="$1"
    local dry_run="${2:-false}"

    local title slug focus_kw meta_desc published_date author pillar

    title=$(get_frontmatter "$file" "seo_title")
    slug=$(get_frontmatter "$file" "slug")
    focus_kw=$(get_frontmatter "$file" "focus_keyword")
    meta_desc=$(get_frontmatter "$file" "meta_description")
    published_date=$(get_frontmatter "$file" "published_date")
    author=$(get_frontmatter "$file" "author")
    pillar=$(get_frontmatter "$file" "pillar")

    if [[ -z "$slug" ]]; then
        error "No slug found in $file — skipping"
        return 1
    fi

    if [[ -z "$title" ]]; then
        # Fallback: extract H1 from content
        title=$(grep '^# ' "$file" | head -1 | sed 's/^# //')
    fi

    log "Processing: $title"
    log "  Slug: $slug"
    log "  Date: $published_date"
    log "  Focus KW: $focus_kw"
    log "  Author: $author"
    log "  Pillar: $pillar"

    if [[ "$dry_run" == "true" ]]; then
        log "  [DRY RUN] Would publish: $title"
        return 0
    fi

    # Check if post already exists by slug
    local existing_id
    existing_id=$(wp post list --post_type=post --name="$slug" --field=ID --path="$WP_PATH" 2>/dev/null || echo "")

    # Convert markdown to HTML
    local html_content
    html_content=$(md_to_html "$file")

    if [[ -n "$existing_id" ]]; then
        log "  Updating existing post ID: $existing_id"
        wp post update "$existing_id" \
            --post_title="$title" \
            --post_content="$html_content" \
            --post_name="$slug" \
            --post_date="$published_date 10:00:00" \
            --path="$WP_PATH" 2>> "$LOG_FILE"
    else
        log "  Creating new post..."
        local new_id
        new_id=$(wp post create \
            --post_type=post \
            --post_title="$title" \
            --post_content="$html_content" \
            --post_name="$slug" \
            --post_status=publish \
            --post_date="$published_date 10:00:00" \
            --porcelain \
            --path="$WP_PATH" 2>> "$LOG_FILE")

        if [[ -n "$new_id" ]]; then
            log "  Created post ID: $new_id"

            # Set Yoast/RankMath SEO meta if available
            if [[ -n "$focus_kw" ]]; then
                wp post meta update "$new_id" "_yoast_wpseo_focuskw" "$focus_kw" --path="$WP_PATH" 2>> "$LOG_FILE" || true
            fi
            if [[ -n "$meta_desc" ]]; then
                wp post meta update "$new_id" "_yoast_wpseo_metadesc" "$meta_desc" --path="$WP_PATH" 2>> "$LOG_FILE" || true
            fi

            # Assign category based on pillar
            if [[ -n "$pillar" ]]; then
                wp post term set "$new_id" category "$pillar" --path="$WP_PATH" 2>> "$LOG_FILE" || true
            fi
        else
            error "  Failed to create post for $file"
            return 1
        fi
    fi

    log "  Done: $title"
}

# -----------------------------------------------------------------------------
# Update existing articles with dateModified for freshness
# -----------------------------------------------------------------------------
refresh_article() {
    local slug="$1"
    local post_id
    post_id=$(wp post list --post_type=post --name="$slug" --field=ID --path="$WP_PATH" 2>/dev/null || echo "")

    if [[ -n "$post_id" ]]; then
        wp post update "$post_id" --post_modified="$(date '+%Y-%m-%d %H:%M:%S')" --path="$WP_PATH" 2>> "$LOG_FILE"
        log "Refreshed dateModified for: $slug (ID: $post_id)"
    else
        warn "Post not found for refresh: $slug"
    fi
}

# -----------------------------------------------------------------------------
# Generate Article schema JSON-LD for a post
# -----------------------------------------------------------------------------
generate_schema() {
    local file="$1"
    local title slug meta_desc published_date author focus_kw

    title=$(get_frontmatter "$file" "seo_title")
    slug=$(get_frontmatter "$file" "slug")
    meta_desc=$(get_frontmatter "$file" "meta_description")
    published_date=$(get_frontmatter "$file" "published_date")
    author=$(get_frontmatter "$file" "author")
    focus_kw=$(get_frontmatter "$file" "focus_keyword")

    cat <<SCHEMA
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "$title",
  "description": "$meta_desc",
  "author": {
    "@type": "Person",
    "name": "$author"
  },
  "publisher": {
    "@type": "Person",
    "name": "BLOND:ISH",
    "url": "https://blondish.world/about/"
  },
  "datePublished": "$published_date",
  "dateModified": "$(date '+%Y-%m-%d')",
  "mainEntityOfPage": "https://blondish.world/journal/$slug/",
  "about": {
    "@type": "Thing",
    "name": "$focus_kw"
  },
  "isPartOf": {
    "@type": "Blog",
    "name": "NRG Zine",
    "url": "https://blondish.world/journal/"
  }
}
SCHEMA
}

# -----------------------------------------------------------------------------
# Audit content freshness — flag articles not updated in 90+ days
# -----------------------------------------------------------------------------
freshness_audit() {
    log "=== Content Freshness Audit ==="
    local ninety_days_ago
    ninety_days_ago=$(date -v-90d '+%Y-%m-%d' 2>/dev/null || date -d '90 days ago' '+%Y-%m-%d')

    for file in "$CONTENT_DIR"/*.md; do
        local slug published_date title
        slug=$(get_frontmatter "$file" "slug")
        published_date=$(get_frontmatter "$file" "published_date")
        title=$(get_frontmatter "$file" "seo_title")

        if [[ "$published_date" < "$ninety_days_ago" ]]; then
            warn "STALE (90+ days): $title [$published_date]"
        else
            log "  Fresh: $title [$published_date]"
        fi
    done
}

# -----------------------------------------------------------------------------
# Main
# -----------------------------------------------------------------------------
main() {
    local command="${1:-help}"

    case "$command" in
        publish-all)
            log "=== Publishing all journal articles ==="
            for file in "$CONTENT_DIR"/*.md; do
                publish_post "$file" "${2:-false}"
            done
            log "=== Done ==="
            ;;
        publish)
            local target="${2:-}"
            if [[ -z "$target" ]]; then
                error "Usage: $0 publish <filename.md>"
                exit 1
            fi
            publish_post "$CONTENT_DIR/$target"
            ;;
        dry-run)
            log "=== Dry run: all journal articles ==="
            for file in "$CONTENT_DIR"/*.md; do
                publish_post "$file" "true"
            done
            ;;
        schema)
            local target="${2:-}"
            if [[ -z "$target" ]]; then
                error "Usage: $0 schema <filename.md>"
                exit 1
            fi
            generate_schema "$CONTENT_DIR/$target"
            ;;
        audit)
            freshness_audit
            ;;
        refresh)
            local target="${2:-}"
            if [[ -z "$target" ]]; then
                error "Usage: $0 refresh <slug>"
                exit 1
            fi
            refresh_article "$target"
            ;;
        help|*)
            echo "NRG Zine Publishing Pipeline"
            echo ""
            echo "Usage: $0 <command> [args]"
            echo ""
            echo "Commands:"
            echo "  publish-all         Publish all markdown files in content/journal/"
            echo "  publish <file.md>   Publish a single markdown file"
            echo "  dry-run             Preview what would be published (no changes)"
            echo "  schema <file.md>    Generate Article schema JSON-LD for a file"
            echo "  audit               Check content freshness (flag 90+ day old articles)"
            echo "  refresh <slug>      Update dateModified for an existing post"
            echo "  help                Show this help message"
            ;;
    esac
}

main "$@"
