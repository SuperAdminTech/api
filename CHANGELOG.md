# Changelog

All notable changes to this project will be documented in this file. See [standard-version](https://github.com/conventional-changelog/standard-version) for commit guidelines.

## [1.5.0](https://github.com/QbitArtifacts/caste/compare/v1.4.4...v1.5.0) (2022-04-26)


### Features

* **config:** add parameter to manage jwt token ttl ([f866a3f](https://github.com/QbitArtifacts/caste/commit/f866a3fdd53fba9b7cf0246d46582447a0868331))
* **jwt:** now we can manage different ttl by application ([4233eb1](https://github.com/QbitArtifacts/caste/commit/4233eb1fd1363a8b39a92b976efafdc99f0ec872))
* **system:** upgrading php 7.4 to 8.1 ([2c962fe](https://github.com/QbitArtifacts/caste/commit/2c962fe509de3356822b836504475242c5f636d0))
* **user:** add new field data to save user info ([2e4e118](https://github.com/QbitArtifacts/caste/commit/2e4e1182418343cade2eb4386031569bdc71267b))
* **users:** allow list users inside application ([32a1c07](https://github.com/QbitArtifacts/caste/commit/32a1c077e483171dbe276b488b982af8c8b72766))
* **users:** Create users from admin ([f754379](https://github.com/QbitArtifacts/caste/commit/f75437903ea4d7fa28f884c409a4d0ecec5d6668))


### Bug Fixes

* **accounts:** Now accounts can be filtered by application ([ad95d2b](https://github.com/QbitArtifacts/caste/commit/ad95d2bb5d91204909a1b8328cac06ab06980014))
* **application:** change get path to sadmin ([85292ed](https://github.com/QbitArtifacts/caste/commit/85292edc73cbf074c3870d0ac1b726700606c8fe))
* **command:** Add argument name to dump command ([522c263](https://github.com/QbitArtifacts/caste/commit/522c26339c3be7c697effe790b55302a8ca07164))
* **command:** Add command to dump/restore database ([a227f01](https://github.com/QbitArtifacts/caste/commit/a227f016d6ca15e108f930509a43aa413d98846c))
* **command:** Add command to remove non validated accounts after 1h ([d7d88d8](https://github.com/QbitArtifacts/caste/commit/d7d88d814e5adcdd422c60d80e4c92d32dbbf755))
* **iris:** return application IRI instead of nested object to improve performance ([52f5c81](https://github.com/QbitArtifacts/caste/commit/52f5c8119c3b61abe0a6df94199ba12cb5e64787))
* **permission:** allow endpoint from admin ([5a1c48d](https://github.com/QbitArtifacts/caste/commit/5a1c48d83a24912e809274c611d27b5a7bd24009))
* **permission:** create permision ([1781c2c](https://github.com/QbitArtifacts/caste/commit/1781c2cae90685edd10832c0b7c98e4a3ed0541d))
* **permission:** create permision ([9958636](https://github.com/QbitArtifacts/caste/commit/99586365ce0167614950eba59777ab51de9fd46a))
* **tests:** adding tests and more fixtures ([92e5324](https://github.com/QbitArtifacts/caste/commit/92e53241df2b711fcfd1613db8a386953626c08a))
* **tests:** created test for delete method in all endpoints ([8184fde](https://github.com/QbitArtifacts/caste/commit/8184fdebd688f042527e4ee0a4c1de15cc936bc6))
* **user, application:** Connect user with application ([52d1c65](https://github.com/QbitArtifacts/caste/commit/52d1c6528802dddacf59299f2c51862856896266))
* **user,account:** Manage enable/disable users/accounts ([53aae08](https://github.com/QbitArtifacts/caste/commit/53aae087ed5921e6aadd5b2481eaeb8115290d64))
* **user:** Allow filter users by application ([3fe2f46](https://github.com/QbitArtifacts/caste/commit/3fe2f463e8db3ebac22e369f9fced859c13615cf))
* **users:** Add application to user when register ([10f0a37](https://github.com/QbitArtifacts/caste/commit/10f0a372a82325f61870f5aad034e9719794e9a7))
* **users:** Admins are able to retrieve a single user in same application ([d792ace](https://github.com/QbitArtifacts/caste/commit/d792ace6bd36458281975aac7374c02ee152b988))
* **users:** return application IRI instead of nested object to improve performance ([08d4727](https://github.com/QbitArtifacts/caste/commit/08d47278b1645efd282262d1dc4c5c2b1a8c5832))
* **users:** return permissions IRI instead of nested object to improve performance ([972d400](https://github.com/QbitArtifacts/caste/commit/972d4006569929c59771c8d35917404259ae4caf))
* **users:** Update users allowed to admins in same application ([d1b73f1](https://github.com/QbitArtifacts/caste/commit/d1b73f12859d4ae765ff39fbabacb1bb99dffff5))

### [1.4.4](https://github.com/QbitArtifacts/caste/compare/v1.4.3...v1.4.4) (2021-02-19)


### Bug Fixes

* **account:** allowing account access + write to admins ([7b1ed88](https://github.com/QbitArtifacts/caste/commit/7b1ed88b284da0f724c60f1116af298f008cb8a8))
* **deps:** updated composer deps ([145a6df](https://github.com/QbitArtifacts/caste/commit/145a6df83d7a83ce50b254e932d6c7b9b617d51f))

### [1.4.3](https://github.com/QbitArtifacts/caste/compare/v1.4.2...v1.4.3) (2021-02-14)


### Bug Fixes

* **filter:** fixed filter bad behaviour ([dd9fe47](https://github.com/QbitArtifacts/caste/commit/dd9fe4783576ea5e9c37b4dbfbee4514f0e58aba))

### [1.4.2](https://github.com/QbitArtifacts/caste/compare/v1.4.1...v1.4.2) (2021-02-14)


### Bug Fixes

* **eager:** removed lazy loading for now ([6e72dd6](https://github.com/QbitArtifacts/caste/commit/6e72dd66c8910137f74ddae48af518f8aceaad88))

### [1.4.1](https://github.com/QbitArtifacts/caste/compare/v1.4.0...v1.4.1) (2021-02-14)


### Bug Fixes

* **deps:** upgraded sentry ([58e9687](https://github.com/QbitArtifacts/caste/commit/58e968714fe03bc04c20c78af32248bd7762bec1))
* removed bad dependency ([400bbaf](https://github.com/QbitArtifacts/caste/commit/400bbaf9b39fdf41c41c0b23c16ebe64df7cee93))

## [1.4.0](https://github.com/QbitArtifacts/caste/compare/v1.3.0...v1.4.0) (2021-02-14)


### Features

* **auth:** implemented api keys ([540d1ad](https://github.com/QbitArtifacts/caste/commit/540d1ad5bb9b98e0d621682cdb5da287ca095ffb))
* **docker:** bumped docker image base to debian 11 ([1661e34](https://github.com/QbitArtifacts/caste/commit/1661e34cfb61c0428b415bad5d14eb8c6ea0c0df))
* **messages:** added account tpl var ([ab79aba](https://github.com/QbitArtifacts/caste/commit/ab79aba227f8eb806ce0743dc2964cdc29359168))
* **messages:** allowing email templates ([3f2283c](https://github.com/QbitArtifacts/caste/commit/3f2283cacd45c243c17304671b4f1a5e71abeddc))
* **messaging,apikeys:** implemented messaging and api keys ([#35](https://github.com/QbitArtifacts/caste/issues/35)) ([175d59c](https://github.com/QbitArtifacts/caste/commit/175d59c804496a87e986e5eb2c3af7fb7e1fd5d9))
* **messenger:** created way to comunicate with users ([ac4772a](https://github.com/QbitArtifacts/caste/commit/ac4772a8736f92e2f299ba77e06404885d424d9d))
* **messenger:** restricting messages to admins ([6f3fbf9](https://github.com/QbitArtifacts/caste/commit/6f3fbf93c6232d2653a9e9c40275a7526b5db4ae))


### Bug Fixes

* **deps:** bumped to php7.4 & updated deps ([0629bf8](https://github.com/QbitArtifacts/caste/commit/0629bf876c6ca96e948e4b95df6cb59db0553c04))
* **deps:** updated composer deps ([a387a4b](https://github.com/QbitArtifacts/caste/commit/a387a4bc2b6d948d727af25985be5374452345ac))
* **mailing:** added mailing errors ([a56844f](https://github.com/QbitArtifacts/caste/commit/a56844f0bd3d08ee037ba2a7dc36a2b7155c00f7))
* **mailing:** fixed bug with mailer_from config var ([571cfea](https://github.com/QbitArtifacts/caste/commit/571cfeaaed450066c5d4caa910fd52c5b113103c))
* **messages:** changed body to text ([9c44b08](https://github.com/QbitArtifacts/caste/commit/9c44b086797baafe1355a5fb2a9db4adfea9140a))
* **messages:** changed messaging to accounts ([4b82204](https://github.com/QbitArtifacts/caste/commit/4b8220438ad84b5ebb9a7532a6364846abdd229a))
* **tests:** upgraded tests ([deaf4e7](https://github.com/QbitArtifacts/caste/commit/deaf4e7f65a6aaaba9d258479ae5acc34bf5137e))

## [1.3.0](https://github.com/QbitArtifacts/caste/compare/v1.2.2...v1.3.0) (2020-11-06)


### Features

* **permissions:** added default permissions to applications ([655d1ab](https://github.com/QbitArtifacts/caste/commit/655d1ab729069f4d72a6ed1a0a49700a5c109ede))


### Bug Fixes

* **permissions:** set app default permissions to users at register ([6115099](https://github.com/QbitArtifacts/caste/commit/6115099203e461d5c374492f051488037b48ce30))

### [1.2.2](https://github.com/QbitArtifacts/caste/compare/v1.2.1...v1.2.2) (2020-11-06)


### Bug Fixes

* **deps:** updated deps ([cccc414](https://github.com/QbitArtifacts/caste/commit/cccc4148edcd7e5e64625209e8c8de6ca4d76983))
* **recover_password:** added some asserts & tested ([ace26fe](https://github.com/QbitArtifacts/caste/commit/ace26fe82dd20d3539907460ad4d10dc2517dc00))

### [1.2.1](https://github.com/QbitArtifacts/caste/compare/v1.2.0...v1.2.1) (2020-11-03)


### Bug Fixes

* **deps:** updated composer deps ([85f566a](https://github.com/QbitArtifacts/caste/commit/85f566a4a884f1f1f11761c3a749b4ab319fbcbb))
* **filter:** hotfix on application filter ([51cf218](https://github.com/QbitArtifacts/caste/commit/51cf2187c6f5cdbbf158c0eac29eeb680e2aae62))

## [1.2.0](https://github.com/QbitArtifacts/caste/compare/v1.1.1...v1.2.0) (2020-10-30)


### Features

* **version:** added version call to api ([4a9252a](https://github.com/QbitArtifacts/caste/commit/4a9252ad042cfb8061156705e3004a58969f4502))


### Bug Fixes

* **deps:** udated composer deps ([ee83361](https://github.com/QbitArtifacts/caste/commit/ee83361fef0dcb9489280a3c059a58465a7e3c54))

### [1.1.1](https://github.com/QbitArtifacts/caste/compare/v1.1.0...v1.1.1) (2020-10-23)


### Bug Fixes

* **recover_password:** fixed bug when username doesn't exist ([eb2e249](https://github.com/QbitArtifacts/caste/commit/eb2e24945cd3c14f9693998254a185ee1543e095))
* **recover_password:** hide email validated from public and improve email html design ([d67642d](https://github.com/QbitArtifacts/caste/commit/d67642d77b4a09c3b7ed8c55f9cfb80080aa5dc5))
* **register:** avoid duplicate account name errors ([24d3d45](https://github.com/QbitArtifacts/caste/commit/24d3d456c28b7bdab506df4c5bc41bcdff63d75c))

## 1.1.0 (2020-10-20)


### Features

* **accounts:** added name to accounts, defaults to username at register account ([eb30341](https://github.com/QbitArtifacts/caste/commit/eb3034170c324447b6b13f4feff2962f3cf28a74))
* **admin:** created relationship between user and applications to have app admins ([531bcd2](https://github.com/QbitArtifacts/caste/commit/531bcd2e34c4a5460f0391670be394c8060a3442))
* **base:** created first entities ([44ae99a](https://github.com/QbitArtifacts/caste/commit/44ae99a93025628a0acf180bc531a6f146b377c1))
* **base:** installed api-platform ([d866d85](https://github.com/QbitArtifacts/caste/commit/d866d85bb5ca48e538be041c55819e0fbefa1151))
* **base:** installed jwt and implemented register ([52c1020](https://github.com/QbitArtifacts/caste/commit/52c1020955e332186cdd4eb8e5b4d8d5f2933bfe))
* **base:** installed sf4 ([1601fc6](https://github.com/QbitArtifacts/caste/commit/1601fc6f90485a1bfa92c3b3a6682e6c4077c23d))
* **ci:** created ci deploy ([5a589e9](https://github.com/QbitArtifacts/caste/commit/5a589e9d023c03bff94dc76b978dfd180e33b9c3))
* **deploy:** created release deploy ([e7002e3](https://github.com/QbitArtifacts/caste/commit/e7002e324f99774a502d3bcd39f89898b09913c3))
* **deps:** bumped minimum php version to 7.3 ([469d83c](https://github.com/QbitArtifacts/caste/commit/469d83cc24e61098be20e58c4c0f13bbe3c660e7))
* **deps:** installed sentry ([c4c8873](https://github.com/QbitArtifacts/caste/commit/c4c8873b2f593ba8d137212de4731259853b9328))
* **entities:** added order filter to all entities ([c1e13b3](https://github.com/QbitArtifacts/caste/commit/c1e13b3d2d9b61f679e2c746dae623bbdcefdc17))
* **entities:** added order filter to all entities ([c4472d0](https://github.com/QbitArtifacts/caste/commit/c4472d03cd2a7ce4493690c669223d109d2314bb))
* **fixtures:** added more stuff to fixtures ([347c141](https://github.com/QbitArtifacts/caste/commit/347c141535e67c13f485dfb239b463d368295ffd))
* **fixtures:** created accounts and permissions fixtures ([3f883d9](https://github.com/QbitArtifacts/caste/commit/3f883d96338a1ee0ea2d095e8d1c3c610f484add))
* **jwt:** added account info to jwt token ([5df7b52](https://github.com/QbitArtifacts/caste/commit/5df7b522b0b1d8c425260c1d0436ffd7adc6ff8f))
* **jwt:** added user id to jwt token ([8c65abf](https://github.com/QbitArtifacts/caste/commit/8c65abf745e91088a6a251e72381ea4b1a84ea9f))
* **logging:** enabled logging features ([2c48ca4](https://github.com/QbitArtifacts/caste/commit/2c48ca400dc450b54fa56e56b933545c1bca5144))
* **pagination:** enabled client items per page ([09840ae](https://github.com/QbitArtifacts/caste/commit/09840aecb032037f604d42645eba5f43c6e078c1))
* **register:** implemented mailing for register ([e13da4d](https://github.com/QbitArtifacts/caste/commit/e13da4dae8f70d378a99134fe177d3fa47366456))
* **register:** implemented mailing for register ([#24](https://github.com/QbitArtifacts/caste/issues/24)) ([4607fdc](https://github.com/QbitArtifacts/caste/commit/4607fdc06073aaa9e8332434f2df69a712df02c4))
* **relationships:** implemented basic relationships between users, permissions and accounts ([b5a5fb4](https://github.com/QbitArtifacts/caste/commit/b5a5fb40b2d3fd0c4558760513258ee553703b38))
* **search:** implemented search filters for user and account, fixes [#19](https://github.com/QbitArtifacts/caste/issues/19) ([7b1caab](https://github.com/QbitArtifacts/caste/commit/7b1caab32b6ac4447042f9e46efcb00b53a56e67))
* **security:** created basic serialization groups, fixes [#10](https://github.com/QbitArtifacts/caste/issues/10) ([e124f0f](https://github.com/QbitArtifacts/caste/commit/e124f0f6de63d9c14bd7a71f081c2de70587738f))
* **security:** created restricted objects to allow only see and change to its owners, needs testing ([dbae02b](https://github.com/QbitArtifacts/caste/commit/dbae02bfe98d91e3019e50d5a14c9eb108524fee))
* **security:** implemented app aware filter and passed all tests ([0c92ded](https://github.com/QbitArtifacts/caste/commit/0c92dedfc95c1c6914edec8daf4edb6995108614))
* **security:** implemented more tests and custom user provider ([fbeced2](https://github.com/QbitArtifacts/caste/commit/fbeced203ad083d8e09d6824b5d5683a307652d7))
* **security:** implemented user permissions ([598e759](https://github.com/QbitArtifacts/caste/commit/598e759e0e11e1536291ae5304be79383c112005))
* **signup:** creating default account and permission when register ([b2ff311](https://github.com/QbitArtifacts/caste/commit/b2ff3113757b20769816894eb43a95b53ff0d8f2))
* **testing:** improved fixtures adding id and implemented more tests ([46fc41d](https://github.com/QbitArtifacts/caste/commit/46fc41d22b460f3a80d3746fd850aefd57126c86))
* **tests:** configured testing ([0af9382](https://github.com/QbitArtifacts/caste/commit/0af93826e6ecf37eaa16552470b263e5988c1fe5))
* **token:** added account name to token ([7706052](https://github.com/QbitArtifacts/caste/commit/7706052742303cc1c649aa7e947ea573006733e7))
* **user:** implemented recover password and some refactor ([18f7438](https://github.com/QbitArtifacts/caste/commit/18f7438d020d709729b22f7ed1ab135257d9bec5))
* **user_management:** implemented create account and give permissions calls ([344c374](https://github.com/QbitArtifacts/caste/commit/344c374f2c81b15bfe5f59ffaa13b9aa1d6d0ec8))


### Bug Fixes

* **account:** fixed account delete, fixes [#20](https://github.com/QbitArtifacts/caste/issues/20) ([8e13f8b](https://github.com/QbitArtifacts/caste/commit/8e13f8ba98e84d802df2c9fd546b070866025ece))
* **accounts:** allow list accs to admins ([c482618](https://github.com/QbitArtifacts/caste/commit/c482618862a25b13f4d9da34106270b1ad3e17d5))
* **application:** fixes [#14](https://github.com/QbitArtifacts/caste/issues/14) ([1b51a02](https://github.com/QbitArtifacts/caste/commit/1b51a02dc40e723c8c7eca45f546575eff221e3c))
* **deploy:** added missed push command to Makefile ([223716a](https://github.com/QbitArtifacts/caste/commit/223716a320476cd4acd8093b8bdf6cb9ea713c47))
* **deploy:** fixed apache vhost ([198349c](https://github.com/QbitArtifacts/caste/commit/198349c527bba28f49f2b764875f78a26e3d9f5b))
* **deploy:** updated deploy ([cb76b7c](https://github.com/QbitArtifacts/caste/commit/cb76b7c89f1c7ccbe1746d9d660663bea9a02c42))
* **deploy:** updated deploy ([ce00191](https://github.com/QbitArtifacts/caste/commit/ce00191d1a6d1992e14fac2fece08265396bc669))
* **deploy:** updated deploy ([396244d](https://github.com/QbitArtifacts/caste/commit/396244dce68daa5c3ac8442a67fa5df690822394))
* **deps:** bump sf version to 4.4.12 ([ced6ddc](https://github.com/QbitArtifacts/caste/commit/ced6ddceff46f4d3b51e164bd011b531d8a081b3))
* **deps:** removed duplicate dependencies and implemented more tests ([88ee0c3](https://github.com/QbitArtifacts/caste/commit/88ee0c36b0828d24930b0acc633b69c98fd3b6f5))
* **deps:** updated composer deps ([2048c45](https://github.com/QbitArtifacts/caste/commit/2048c456827ec8b82d689f4404f184af954e859f))
* **deps:** updated composer deps ([5eaf8cf](https://github.com/QbitArtifacts/caste/commit/5eaf8cf1aa6822cff940632cf6eb84b86714b981))
* **deps:** updated composer deps ([cf23f81](https://github.com/QbitArtifacts/caste/commit/cf23f812fa80b7242ce9516c0024c58d8f84b60e))
* **deps:** updated composer deps ([38cc2c2](https://github.com/QbitArtifacts/caste/commit/38cc2c2ce0b6a58cf2c4760c74206445ac9b7d43))
* **deps:** updated composer deps ([8ca7314](https://github.com/QbitArtifacts/caste/commit/8ca73142c4c2df555d50df433ca6fbdea8befa07))
* **deps:** updated composer deps ([837f639](https://github.com/QbitArtifacts/caste/commit/837f63968cecf2cbae77ace6585399ec848b36a2))
* **deps:** updated dependencies ([923ac9c](https://github.com/QbitArtifacts/caste/commit/923ac9ceefc4ab33553c03d498f9563b18abf6ea))
* **devtools:** installed standard-version to manage conventional commits ([e831ecd](https://github.com/QbitArtifacts/caste/commit/e831ecd4070da2f0e85adcdad1ff0944bf0a3eca))
* **doc:** updated readme ([ec6e4c7](https://github.com/QbitArtifacts/caste/commit/ec6e4c7510ec0d653f8e911509a6808254b6ce8f))
* **docker:** fixed docker entrypoint ([3176546](https://github.com/QbitArtifacts/caste/commit/3176546ad4267d4612bc92c28a86161fdd42e0d4))
* **docker:** fixed docker entrypoint ([7665717](https://github.com/QbitArtifacts/caste/commit/76657172c00f4ae790ff15202b9406c5a8d01ac7))
* **docker:** fixed docker entrypoint ([a0818d1](https://github.com/QbitArtifacts/caste/commit/a0818d11d3a87b284055f71f835bb619ca34bbb1))
* **docker:** fixed docker entrypoint ([a31c741](https://github.com/QbitArtifacts/caste/commit/a31c74110909f53d0ee5a127c09b1ae8e376e0af))
* **docker:** fixed docker entrypoint ([8944849](https://github.com/QbitArtifacts/caste/commit/894484992908631f0b17912d131f91b85786976a))
* **docker:** fixed docker entrypoint ([62f0df8](https://github.com/QbitArtifacts/caste/commit/62f0df8255a94229cd9e75c64ba053f76676ca58))
* **docker:** fixed wrong username env var for mariadb ([b2510eb](https://github.com/QbitArtifacts/caste/commit/b2510ebcb555a87dc96c3b694d2dfebf9f12da35))
* **docs:** imported readme from another project ([06a4baf](https://github.com/QbitArtifacts/caste/commit/06a4baf5bd6f164b14edd0c83b590d592c73508b))
* **docs:** updated documentation for custom endpoints ([5d56a62](https://github.com/QbitArtifacts/caste/commit/5d56a62feb312fabfa923b2abfd486f2a088e889))
* **env:** added missed JWT_TTL env var to .env ([475896e](https://github.com/QbitArtifacts/caste/commit/475896e5d0820454ada9e95ab0f335b94304bf37))
* **fixtures:** small changes in fixtures ([30458af](https://github.com/QbitArtifacts/caste/commit/30458afcd46d7b90dce223c3d5875a8a19eb68fc))
* **login:** implemented custom authentication system with applications ([b6a4c5d](https://github.com/QbitArtifacts/caste/commit/b6a4c5daf0c2faf39daf0eb97ef476e0c1bae8a6))
* **mailing:** added expiration information to recover password email templates ([fdef671](https://github.com/QbitArtifacts/caste/commit/fdef671c835e0e676163f390769a6f64a39b141e))
* **new_account:** fixed new account tests ([11114dd](https://github.com/QbitArtifacts/caste/commit/11114ddb868ea3aea36c32ed4285697f69a39ffb))
* **normalization:** common fields were not showing ([c032be2](https://github.com/QbitArtifacts/caste/commit/c032be2d3b8db4d662f5a214d26a925856176120))
* **permission:** added user-account uniqueness check ([2557651](https://github.com/QbitArtifacts/caste/commit/2557651d6cbee25cf4ef7ce5b8adfe5cc9f937cb))
* **permission:** allowed read and write to owners ([a795c6a](https://github.com/QbitArtifacts/caste/commit/a795c6ae14755b4d870d7c072604e95a1d1b29db))
* **prod:** installed apache pack ([d6a8310](https://github.com/QbitArtifacts/caste/commit/d6a8310e84667d11049461834000cb655af11e25))
* **refactor:** change relationship from users <-> apps to accounts <-> apps ([#23](https://github.com/QbitArtifacts/caste/issues/23)) ([ca791a3](https://github.com/QbitArtifacts/caste/commit/ca791a3d8077175bc867e2e76f1fed07ec2f7152))
* **refactor:** moved auth tests to its own dir ([cdc8bea](https://github.com/QbitArtifacts/caste/commit/cdc8bea8972a489cd89ee2e27641de63f7bceda9))
* **refactor:** refactored signup and finished create new account ([fda0c3e](https://github.com/QbitArtifacts/caste/commit/fda0c3e24adf5b325f014c51ca0b752c78f9ec84))
* **refactor:** renamed app_token to realm ([928faa8](https://github.com/QbitArtifacts/caste/commit/928faa80a629c86f9f8af46a958f9fe2e0b25e47))
* **register:** bugfix in register ([9f9ef56](https://github.com/QbitArtifacts/caste/commit/9f9ef56ee4c08c54e9836f6c800c06031c0b9e18))
* **register:** bugfix in register ([96966fa](https://github.com/QbitArtifacts/caste/commit/96966fa78572c4e2345a1e50356663c9a393f46b))
* **routing:** refactored all routes be more clear what users can call each route ([f089c23](https://github.com/QbitArtifacts/caste/commit/f089c23400651e4284df866228f97bce9da82af4))
* **security:** created reader filter ([27ca5ce](https://github.com/QbitArtifacts/caste/commit/27ca5ce7a05ad6673d501fe249586ae469d77c14))
* **security:** hide id, created, updated to public calls, needed authenticated user to get it ([2c27ef0](https://github.com/QbitArtifacts/caste/commit/2c27ef0b055f36e65fa32502e9077ec8b4ab1ba2))
* **signup:** avoid register user not saved ([0d45fef](https://github.com/QbitArtifacts/caste/commit/0d45fefd3ba48151e9a8fd742fc9544274c71b64))
* **signup:** avoid register user twice ([4322744](https://github.com/QbitArtifacts/caste/commit/432274471b120e054f2d221ac0e4ab7e9dd9c2ee))
* **testing:** testing POST /applications with all user types, fixes [#15](https://github.com/QbitArtifacts/caste/issues/15) ([4560342](https://github.com/QbitArtifacts/caste/commit/4560342e9354ef54db6b6d7f61da97e2361aab0c))
* **tests:** fixed credentials in SignInTest ([0e434c3](https://github.com/QbitArtifacts/caste/commit/0e434c38650afffc63f2afe86286c3217cd35e12))
* **tests:** fixed paths in tests ([59ff663](https://github.com/QbitArtifacts/caste/commit/59ff663a6867baeb42e31ab7c7671157c8b69e8d))
* **tests:** fixed paths in tests, again ([0e8cee6](https://github.com/QbitArtifacts/caste/commit/0e8cee61b96793ba94a8a7cfd9c6271bf799bea7))
* **tests:** implemented more tests for sign up ([20a69b7](https://github.com/QbitArtifacts/caste/commit/20a69b7e7110288526caaf52c7bfd0eb54db5772))
* **tests,refactor:** created sign up tests and refactor ([0768b44](https://github.com/QbitArtifacts/caste/commit/0768b444ccb1944a1197e7889e82938214ae5d61))
* **token:** added missed permissions to token ([64cdb89](https://github.com/QbitArtifacts/caste/commit/64cdb89da240a6fb723f3ec6c777b2d55fafafc6))
* **user:** fixed user delete, fixes [#17](https://github.com/QbitArtifacts/caste/issues/17) ([5c32065](https://github.com/QbitArtifacts/caste/commit/5c32065f3a8bbc48677fd0e9ffe0ee7cc539ac8b))
* **user:** removed unique validation code to allow migrations ([85499a3](https://github.com/QbitArtifacts/caste/commit/85499a3dcbb5f3e7e784e2f0eb8a565da79245d6))
* **user:** removed unique validation code to allow migrations ([082f987](https://github.com/QbitArtifacts/caste/commit/082f987da34faaf016d3d63dba151f202de77995))
* **users:** disallow admins to list users, only accounts can list ([4c661d4](https://github.com/QbitArtifacts/caste/commit/4c661d4b9d66524d1a775ce72da005afc92195fa))
* set base abstract ([f8c8e2b](https://github.com/QbitArtifacts/caste/commit/f8c8e2bcf04270d79386e56f8aa7d2e923c96606))
