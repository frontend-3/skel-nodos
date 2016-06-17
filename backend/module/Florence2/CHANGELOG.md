# Florence2 CHANGELOG

### 2016-06-06
- Rename AbstractFormManager to AbstractFormService. Version is kept at v1.0.0.

- Florence2
    - Bumped version to v2.0.0.
    - Change how YAML files are read. Instead of passing an absolute path to the YAML file, you define it in a config file (e.g. `module.config.php`) under the florence2.forms keys. This is better because it will allow reuse and modularization.

- AbstractFormService
    - Bump version to v2.0.0.
    - Remove `AbstractFormService::yaml()`.

- Implemented ConsoleController v1.0.0 for exporting Form to HTML


### 2016-05-31
First commit. Current versions:

- AbstractFormManager v1.0.0
- Definition v1.1.2
- Fieldset v1.0.0
- Florence2 v1.2.1
- FormElementManager v1.0.1

