# Dalil Tech Publish

A self-hosted social media management service. Schedule and publish to TikTok, Instagram, YouTube, LinkedIn, X, and more from one calendar, with an AI copilot for drafting.

- Live service: https://publish.dalil-tech.com
- Product page: https://dalil-tech.com/publish

## What this is

Dalil Tech Publish is operated by Mohammed Almuhanna (Dalil Tech) in Saudi Arabia. It is a branded, self-hosted deployment built on [TryPost](https://github.com/trypostit/trypost), the open-source social media scheduler, rebranded to the Dalil Tech design system, with additional work: an owned-analytics "Insights" section, TikTok composer fixes, and UI changes.

## License and source

Licensed under the GNU Affero General Public License v3.0 (AGPL-3.0), inherited from TryPost. Because the service runs a modified version over a network, its source is published here under AGPL Section 13. See [LICENSE](LICENSE). Credit to the TryPost authors for the upstream project.

## Self-hosting

This repository is the Dalil Tech deployment fork. To self-host the upstream tool instead, start from [TryPost](https://github.com/trypostit/trypost) and its [docs](https://docs.trypost.it). App configuration uses standard Laravel `.env` (see `.env.example`); production secrets are not committed.
