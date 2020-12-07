# Changelog

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
