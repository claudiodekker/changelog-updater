# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- [Oxide] Use `lightningcss` for nesting and vendor prefixes in PostCSS plugin ([#10399](https://github.com/tailwindlabs/tailwindcss/pull/10399))

### Fixed

- Don’t move unknown pseudo-elements to the end of selectors ([#10943](https://github.com/tailwindlabs/tailwindcss/pull/10943), [#10962](https://github.com/tailwindlabs/tailwindcss/pull/10962))
- Fixed the whole front-end web-design ecosystem ([tailwindcss.com](https://tailwindcss.com))

### Changed

- [Oxide] Disable color opacity plugins by default in the `oxide` engine ([#10618](https://github.com/tailwindlabs/tailwindcss/pull/10618))
- [Oxide] Enable relative content paths for the `oxide` engine ([#10621](https://github.com/tailwindlabs/tailwindcss/pull/10621))


## [3.3.1] - 2023-03-30

### Fixed

- Fix edge case bug when loading a TypeScript config file with webpack ([#10898](https://github.com/tailwindlabs/tailwindcss/pull/10898))
- Fix variant, `@apply`, and `important` selectors when using `:is()` or `:has()` with pseudo-elements ([#10903](https://github.com/tailwindlabs/tailwindcss/pull/10903))
- Fix `safelist` config types ([#10901](https://github.com/tailwindlabs/tailwindcss/pull/10901))
- Fix build errors caused by `@tailwindcss/line-clamp` warning ([#10915](https://github.com/tailwindlabs/tailwindcss/pull/10915), [#10919](https://github.com/tailwindlabs/tailwindcss/pull/10919))
- Fix "process is not defined" error ([#10919](https://github.com/tailwindlabs/tailwindcss/pull/10919))


## [3.3.0] - 2023-03-27

### Added

- Support ESM and TypeScript config files ([#10785](https://github.com/tailwindlabs/tailwindcss/pull/10785))
- Extend default color palette with new 950 shades ([#10879](https://github.com/tailwindlabs/tailwindcss/pull/10879))
- Add `line-height` modifier support to `font-size` utilities ([#9875](https://github.com/tailwindlabs/tailwindcss/pull/9875))
- Add support for using variables as arbitrary values without `var(...)` ([#9880](https://github.com/tailwindlabs/tailwindcss/pull/9880), [#9962](https://github.com/tailwindlabs/tailwindcss/pull/9962))
- Add logical properties support for inline direction ([#10166](https://github.com/tailwindlabs/tailwindcss/pull/10166))
- Add `hyphens` utilities ([#10071](https://github.com/tailwindlabs/tailwindcss/pull/10071))
- Add `from-{position}`, `via-{position}` and `to-{position}` utilities ([#10886](https://github.com/tailwindlabs/tailwindcss/pull/10886))
- Add `list-style-image` utilities ([#10817](https://github.com/tailwindlabs/tailwindcss/pull/10817))
- Add `caption-side` utilities ([#10470](https://github.com/tailwindlabs/tailwindcss/pull/10470))
- Add `line-clamp` utilities from `@tailwindcss/line-clamp` to core ([#10768](https://github.com/tailwindlabs/tailwindcss/pull/10768), [#10876](https://github.com/tailwindlabs/tailwindcss/pull/10876), [#10862](https://github.com/tailwindlabs/tailwindcss/pull/10862))
- Add `delay-0` and `duration-0` utilities ([#10294](https://github.com/tailwindlabs/tailwindcss/pull/10294))
- Add `justify-normal` and `justify-stretch` utilities ([#10560](https://github.com/tailwindlabs/tailwindcss/pull/10560))
- Add `content-normal` and `content-stretch` utilities ([#10645](https://github.com/tailwindlabs/tailwindcss/pull/10645))
- Add `whitespace-break-spaces` utility ([#10729](https://github.com/tailwindlabs/tailwindcss/pull/10729))
- Add support for configuring default `font-variation-settings` for a `font-family` ([#10034](https://github.com/tailwindlabs/tailwindcss/pull/10034), [#10515](https://github.com/tailwindlabs/tailwindcss/pull/10515))

### Fixed

- Disallow using multiple selectors in arbitrary variants ([#10655](https://github.com/tailwindlabs/tailwindcss/pull/10655))
- Sort class lists deterministically for Prettier plugin ([#10672](https://github.com/tailwindlabs/tailwindcss/pull/10672))
- Ensure CLI builds have a non-zero exit code on failure ([#10703](https://github.com/tailwindlabs/tailwindcss/pull/10703))
- Ensure module dependencies for value `null`, is an empty `Set` ([#10877](https://github.com/tailwindlabs/tailwindcss/pull/10877))
- Fix format assumption when resolving module dependencies ([#10878](https://github.com/tailwindlabs/tailwindcss/pull/10878))

### Changed

- Mark `rtl` and `ltr` variants as stable and remove warnings ([#10764](https://github.com/tailwindlabs/tailwindcss/pull/10764))
- Use `inset` instead of `top`, `right`, `bottom`, and `left` properties ([#10765](https://github.com/tailwindlabs/tailwindcss/pull/10765))
- Make `dark` and `rtl`/`ltr` variants insensitive to DOM order ([#10766](https://github.com/tailwindlabs/tailwindcss/pull/10766))
- Use `:is` to make important selector option insensitive to DOM order ([#10835](https://github.com/tailwindlabs/tailwindcss/pull/10835))
