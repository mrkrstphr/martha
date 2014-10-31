# martha

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/martha-ci/martha/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/martha-ci/martha/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/69b45127-c0df-4d8f-a6df-a0b5ee029261/mini.png)](https://insight.sensiolabs.com/projects/69b45127-c0df-4d8f-a6df-a0b5ee029261)

> Because not all Continuous Integration solutions need to be named after old men.

## Installation

Installation is simple:

    git clone https://github.com/martha-ci/martha.git
    cd martha
    curl -sS https://getcomposer.org/installer | php
    php composer.phar install

Edit your config/autoload/system.local.php file and fill out the proper database connection information.

## Authentication/Authorization

Not available yet.

## Project Setup

Projects can be setup in the UI by clicking on 'New Project.' Plugins exist to pull projects from remote
sources, such as GitHub, and can be installed via Composer (see below).

## Build Configuration

Martha looks for a `build.yml` file in the base directory of your project. This file outlines the steps 
Martha should take to build your project and report its status. 

An example configuration: 

```yaml
pre:
  - curl -s https://getcomposer.org/installer | php
  - php composer.phar install --prefer-source --dev
build:
  - phpcs --standard=PSR2 -n --report=checkstyle --report-file=${outputdir}/checkstyle.xml src/
```

The `pre:` section outlines the various steps to take to prepare the project to be built. These are simple
commands to be run. Any used command line tool must be installed on the system.

The `build:` section outlines various steps to take to build the project. Again, these are simple
commands to be run. Any used command line tool must be installed on the system.

The `post:` section outlines various steps to do after a project has built. The `failure:` section only runs 
if the build is determined to be a failure, and the `success:` section runs only if the build is determined
to be successful. 

### Variables

The following variables can be defined and will be replaced with real world values during parsing:

| Variable        | Replaced With           |
| ------------- |:-------------:| -----:|
| ${outputdir}      | The output directory for the build where artifact files can be stored |

### Artifacts

During the build process, artifacts, or output files, can be stored in the build directory to be later 
referenced when reviewing the build. These can also be used by various plugins to draw graphs or output
general information or statistics. 

## Using Plugins

Any plugin created for Martha can be installed by Composer.

TODO: document how.
