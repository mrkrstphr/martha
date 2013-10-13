# Martha CI Documentation

## Authentication

By default, without any configuration, Martha is open, allowing all visitors to view and manage data, which may be
perfectly fine on a corporate intranet or local machine.

Currently, Martha provides three methods for authentication:

 1. Database
 2. LDAP
 3. GitHub

### Database Authentication

Database authentication uses Martha's local database to store user information and authenticates against it when
users attempt to log in. To enable it, add the following to the `config/autoload/martha.php` configuration file:

```php
// martha.php
<?php

return [
    // ...
    'martha' => [
        // ...
        'authentication' => [
            'mode' => 'strict',
            'method' => 'database',
        ],
        // ...
    ],
    // ...
];
```

#### Authentication Modes

When `mode` = `strict`, Martha will require a visitor to login to see anything. When `mode` = `lenient`, a visitor
can view project and build information, but cannot create, edit, or remove any projects or builds, or edit information
about users. Under `lenient`, visitors are read-only. Under `strict`, visitors are not allowed; only authenticated
users.

### LDAP Authentication

??? TODO: Figure this out ???

### GitHub Authentication

Martha allows authentication with a GitHub account. To use it, you'll have to create an application on GitHub
(under your account, edit profile, Applications) to obtain an OAuth Client ID and Client Secret. Once you've have this
information, you can enable GitHub authentication in the `config/autoload/martha.php` configuration file:

```php
// martha.php
<?php

return [
    // ...
    'martha' => [
        // ...
        'authentication' => [
            'method' => 'github',
            'github_client_id' => 'YOUR_CLIENT_ID',
            'github_client_secret' => 'YOUR_CLIENT_SECRET',
        ],
        // ...
    ],
    // ...
];
```

With `github` enabled, an *Authenticate with GitHub* button will appear on the login page.

#### Limiting Users

If you only want members of a certain organization (or organizations) to be able to login, you can specify them in
the `config/autoload/martha.php` configuration file:

```php
// martha.php
<?php

return [
    // ...
    'martha' => [
        // ...
        'authentication' => [
            'method' => 'github',
            'github_client_id' => 'YOUR_CLIENT_ID',
            'github_client_secret' => 'YOUR_CLIENT_SECRET',
            'github_allowed_organizations' => 'your-origanization', // or...
            'github_allowed_organizations' => ['your-first-organization', 'your-second-organization']
        ],
        // ...
    ],
    // ...
];
```

#### Coupling with Database Authentication

GitHub authentication and database authentication can both be enabled at the same time. The user will be displayed
a login form, as well as a button to authenticate with GitHub. Upon authentication, a row is stored in the database
for that authenticated GitHub user's email address.

```php
// martha.php
<?php

return [
    // ...
    'martha' => [
        // ...
        'authentication' => [
            'method' => ['database', 'github'],
            // ...
        ],
        // ...
    ],
    // ...
];
```
