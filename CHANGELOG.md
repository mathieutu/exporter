# Changelog

<a name="3.3.2"></a>
## 3.3.2 (2024-03-10)

### Fixed

- 🐛 Fix types for case where nesting in null target. [[00396ae](https://github.com/mathieutu/exporter/commit/00396ae19de1fdb6183534d53ab1868ec5968e39)]


<a name="3.3.1"></a>
## 3.3.1 (2023-07-01)

### Fixed

- 🐛 Fix PHP typing when exporting wildcards from collections (like Laravel relations). [[9f04ef0](https://github.com/mathieutu/exporter/commit/9f04ef0bd74e8f3b6a2a09ff762e34a192a7ae1d)]


<a name="3.3.0"></a>
## 3.3.0 (2023-02-22)

### Changed

- ⬆️ Upgrade PHPUnit. [[f125b93](https://github.com/mathieutu/exporter/commit/f125b938c6fcc42836017f508b2e9feafba3858e)]
- ⬆️ Upgrade versions and requirements. [[2b947b7](https://github.com/mathieutu/exporter/commit/2b947b7d3f717b333aabc5986a2552fefc8091f0)]

### Fixed

- 💚 Upgrade Github actions versions. [[312a834](https://github.com/mathieutu/exporter/commit/312a834cb88633d8293527b7b9fabcf262750593)]
- 💚 Fix coverage upload. [[48b7d47](https://github.com/mathieutu/exporter/commit/48b7d47750a384bb9066f765d2735977721cbd36)]

### Miscellaneous

- 🤖 Upgrade to GitHub-native Dependabot ([#18](https://github.com/mathieutu/exporter/issues/18)) [[a877dde](https://github.com/mathieutu/exporter/commit/a877dde3e51317d2da07e69b750584ea3c9670a9)]
- 🏷️ Update types and syntax to PHP8. [[b1e5ff9](https://github.com/mathieutu/exporter/commit/b1e5ff9c0c45baf3c88ea837a38fe085fee2a0ff)]


<a name="3.2.0"></a>
## 3.2.0 (2020-12-08)

### Added

- ✨ Add getter usage with different case than camel. [[9d735c6](https://github.com/mathieutu/exporter/commit/9d735c6c53cabce4af3af5d34d4ecf77cde31be4)]


<a name="3.1.0"></a>
## 3.1.0 (2020-12-08)

### Added

- ✅ Add test to validate behaviour of nested, wildcard and aliases. (fix [#3](https://github.com/mathieutu/exporter/issues/3)) [[ac796fa](https://github.com/mathieutu/exporter/commit/ac796fafd1dc9369d389946f76a648823988d0c0)]
- ✨ Add ability to export directly from an iterable with root wildcard. (fix [#1](https://github.com/mathieutu/exporter/issues/1)) [[539235e](https://github.com/mathieutu/exporter/commit/539235e0cbac6df8dde981cc6aefc7fc6caad0dd)]
- ✨ Add ability to export directly a value if single string or int field at root. (fix [#17](https://github.com/mathieutu/exporter/issues/17)) [[e43550d](https://github.com/mathieutu/exporter/commit/e43550df8776c80d365ecbe220fc9023fbc4a004)]

### Miscellaneous

- 📝 Update outdated readme screenshots. [[2c7d032](https://github.com/mathieutu/exporter/commit/2c7d03244eb81928abfbc3dd6565fd51e4d68b88)]


<a name="3.0.0"></a>
## 3.0.0 (2020-12-07)

💥 BREAKING CHANGE: 
The return of export method is not a TightenCo collection, but a standard Laravel Collection.
See https://github.com/tighten/collect/issues/217#issuecomment-688499481

### Added

- ✨ Add changelog. [[bf0dffa](https://github.com/mathieutu/exporter/commit/bf0dffa197873b98adfaec7d962f71e16dd20a80)]

### Changed

- ⬆️ Remove php7.2 compatibility (because of illuminate/collections requirement). [[0bbb7a9](https://github.com/mathieutu/exporter/commit/0bbb7a98605a75e5b8b865100bf90f11d8e56a42)]
- 👽 Replace tightenco/collect with illuminate/collections. [[dbf91ef](https://github.com/mathieutu/exporter/commit/dbf91efae47e2557a014a45fac759b462fdbf1f5)]
- ⬆️ Upgrade dev dependencies. [[6774196](https://github.com/mathieutu/exporter/commit/677419618effcffc2b844fe2d641f84a2828dafe)]

### Miscellaneous

-  👷 Replace TravisCI with Github Actions. [[fefaf33](https://github.com/mathieutu/exporter/commit/fefaf3397cb7298d173742be4bc2681e34a171ef)]


<a name="2.1.0"></a>
## 2.1.0 (2020-06-11)

### Added

- ✅ Add tests for aliases features. [[0ed44eb](https://github.com/mathieutu/exporter/commit/0ed44ebdf98de113f3ec1df6305f2c398e7c83fe)]
- ✨ Add syntax for aliasing keys of nested object. [[dcaeeba](https://github.com/mathieutu/exporter/commit/dcaeeba8f09c168e40b18ecadcf72f34a4bd308b)]

### Changed

- ⬆️ Update tightenco/collect requirement from ^6.0 to ^7.0 ([#9](https://github.com/mathieutu/exporter/issues/9)) [[6901a82](https://github.com/mathieutu/exporter/commit/6901a8262979624b8833a37d052f14708c30d536)]

### Miscellaneous

- 📝 Update documentation. [[4512799](https://github.com/mathieutu/exporter/commit/4512799bd516d92fb89fd8a1dda435ade2869f5d)]


<a name="2.0.1"></a>
## 2.0.1 (2019-12-03)

### Fixed

- 🐛 Fix bug detected with php7.4 migration. &quot;Object of class [...] could not be converted to string&quot; [[de48587](https://github.com/mathieutu/exporter/commit/de485879167ceb4a3fa189f335c420fbf270edb6)]

### Miscellaneous

- 📦 Update tightenco/collect. ([#8](https://github.com/mathieutu/exporter/issues/8)) [[2dbfb29](https://github.com/mathieutu/exporter/commit/2dbfb298849d5fbc656297f89396a684fa258243)]
- 📝 Fix typo in Readme. [[c550112](https://github.com/mathieutu/exporter/commit/c5501125f07435c0b07fe8b44409a789aa11560a)]


<a name="2.0.0"></a>
## 2.0.0 (2018-02-19)

### Added

- ✅ Add tests about private properties and getters. [[8030957](https://github.com/mathieutu/exporter/commit/8030957c0111463d3ef6114978b79d18e38b258b)]
- ✨ 💚 Add new static method exportFrom and increase quality code. [[1f886f5](https://github.com/mathieutu/exporter/commit/1f886f530d630ac4e6067733d4bffb1b21613d92)]

### Changed

- 🎨 Increase code quality. [[045f88b](https://github.com/mathieutu/exporter/commit/045f88b70472317754aeb61c03e9f4f3b1e9e9a5)]

### Miscellaneous

- 📝 Update Readme. [[4ce73e0](https://github.com/mathieutu/exporter/commit/4ce73e0dac61a0d9cd38faf07dbd13ab099239c2)]
- 💥 💚 Update PHP version required. [[5920533](https://github.com/mathieutu/exporter/commit/5920533a65086131a6dcf9a99feb7b4358c44fd6)]


<a name="1.1.0"></a>
## 1.1.0 (2018-02-18)

### Added

- ✨ Export private property from getters. [[c9a540f](https://github.com/mathieutu/exporter/commit/c9a540f5bc00e966c38a3d8e30d5a16f1229795b)]

### Changed

- 🚨 Improve Scrutinizer code quality score, and add code coverage badge. [[84a232c](https://github.com/mathieutu/exporter/commit/84a232c641710c9d2357b1c876f296f47f8502de)]

### Miscellaneous

- 📝 Add use cases. [[be65d8f](https://github.com/mathieutu/exporter/commit/be65d8f910c081f91e77d49261d26ffc18ac007d)]
- 📝 Fix readme indentation. [[2d32547](https://github.com/mathieutu/exporter/commit/2d325474527b334d73774d3c81b2f1fc78bd7cf5)]


<a name="1.0.0"></a>
## 1.0.0 (2018-01-04)

### Added

- 🎉 First commit. [[374d143](https://github.com/mathieutu/exporter/commit/374d143774dbbced091c4be9b1973d711c9e1259)]

### Miscellaneous

- 📝 Add Readme. [[7972bdf](https://github.com/mathieutu/exporter/commit/7972bdf3b75836b4a333bede108012dc478767ee)]
