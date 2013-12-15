# Martha Events

Martha CI contains an event system that allows plugin developers to listen for and provide additional functionality
to the system.

## Build Events

The following build events have to do with the start, finish, and success or failure of a build:

| Event | Description  |
| ------ | ----- |
| **build.started** | This event is triggered when a build has started. This event happens immediately after updating the build database record status as *running*, and before any build processing occurs.  |
| **build.completed** | This event is triggered when a build has completed, either success or failure, after the build database record status is updated and after all post buuild processing has finished.  |
| **build.success** | This event is triggered when a build has successfully completed, after the build database record status is updated as *success* and after all post buuild processing has finished.   |
| **build.failure** | This event is triggered when a build has successfully completed, after the build database record status is updated as *failure* and after all post buuild processing has finished.   |
| **build.error** | This event is triggered when the build process has encountered an error and cannot continue. This event does not mean the build would have resulted in failure.  |

The following events are triggered before a build event occurs:

| Event | Description |
| ------ | ----- |
| **build.pre.environment** | Triggered before setting up the build environment. |
| **build.pre.source** | Triggered before retrieving the source code from source control. |
| **build.pre.cleanup** | Triggered before starting the process of cleaning up the build environment (removing source code, etc) |

The following events are triggered after a build event occurs:

| Event | Description |
| ------ | ----- |
| **build.post.environment** | Triggered after the build environment has been setup. |
| **build.post.source** | Triggered after the source code has been retrieved from source control. |
| **build.post.cleanup** | Triggered after finishign the process of cleaning up the build environment (removing sourec code, etc) |
