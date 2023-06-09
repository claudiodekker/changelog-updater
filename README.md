# Changelog Updater

A PHP library to programmatically update changelogs based on the "Keep a Changelog" 1.0.0 format.

## Usage

### GitHub Action

This library can be used as a GitHub Action to automatically update your changelog when a pull request is merged. You can [see an example of what this looks like here](https://github.com/claudiodekker/changelog-updater-example). To set up the GitHub Action, follow the steps below:

1. Create a new file in your repository at `.github/workflows/update_changelog.yml`

2. Copy the following content into the `update_changelog.yml` file:

   ```yaml
   name: Update Changelog on PR Merge

   on:
     pull_request_target:
       types:
         - closed

   jobs:
     update-changelog:
       name: Update Changelog
       runs-on: ubuntu-latest
       if: github.event.pull_request.merged == true

       permissions:
         contents: write

       steps:
         - name: Checkout code
           uses: actions/checkout@v3
           with:
             ref: ${{ github.event.pull_request.base.ref }}
             fetch-depth: 0

         - name: Determine changelog section to update
           id: sections
           run: |
             section=""
             labels=$(echo '${{ toJSON(github.event.pull_request.labels.*.name) }}' | jq -r '.[]')
             for label in $labels; do
               lower_label=$(echo "$label" | tr '[:upper:]' '[:lower:]')
               case "$lower_label" in
                 enhancement|feature) section="Added"; break;;
                 bug|bugfix|fix|patch) section="Fixed"; break;;
                 change) section="Changed"; break;;
                 optimization|improvement|performance|refactor) section="Optimized"; break;;
                 deprecation|deprecated) section="Deprecated"; break;;
                 revert) section="Reverted"; break;;
                 removal) section="Removed"; break;;
                 security) section="Security"; break;;
               esac
             done

             if [ -z "$section" ]; then
               echo "No matching label found for changelog entry, skipping changelog update."
               exit 0
             else
               echo "section=$section" >> $GITHUB_OUTPUT
             fi

         - name: Add entry to CHANGELOG.md
           if: steps.sections.outputs.section != ''
           uses: claudiodekker/changelog-updater@master
           with:
             section: "${{ steps.sections.outputs.section }}"
             entry-text: "${{ github.event.pull_request.title }}"
             entry-link: "${{ github.event.pull_request.html_url }}"

         - name: Commit updated CHANGELOG
           if: steps.sections.outputs.section != ''
           uses: stefanzweifel/git-auto-commit-action@v4
           with:
             branch: ${{ github.event.pull_request.base.ref }}
             commit_message: "Update CHANGELOG.md w/ PR #${{ github.event.pull_request.number }}"
             file_pattern: CHANGELOG.md
   ```
    
3. Commit and push the file to your repository. 
 
Once done, the  GitHub Action will automatically update your changelog when a pull request with a matching label is merged.
