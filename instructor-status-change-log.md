# Instructor Status Tab Changes

## Overview
This file tracks the changes made to support three instructor tabs in the admin instructor list: All, Active, and Inactive.

## Changes made

### 1. `Admin/Instructors.php`
- Added a `status` query parameter to the page.
- Added a tabbed navigation UI for `All`, `Active`, and `Inactive` instructor views.
- Updated the page header to reflect the selected tab and record count.
- Preserved pagination while keeping the selected tab active across page links.
- Updated the instructor fetch call to include the selected status filter.

### 2. `Controller/Admin/Instructor.php`
- Added `normalizeInstructorStatus()` to validate and normalize the `status` parameter.
- Updated `getSomeInstructors()` to accept a `status` argument and return filtered instructors.
- Updated `getCount()` to accept a `status` argument and return the matching count.

### 3. `Models/Instructor.php`
- Added `getSomeByStatus($offset, $num, $status)` for paginated instructor lists filtered by status.
- Added `countByStatus($status)` for instructor counts filtered by status.
- Kept the existing `getSome()` and `count()` methods unchanged for backward compatibility.

## Behavior and concept
- `Active` means the instructor account is currently enabled.
- `Inactive` means the instructor account is currently disabled/blocked by admin and can be reactivated.
- `All` shows every instructor record regardless of status.
- This is a soft-disable mechanism, not a permanent deletion or removal.

## Notes
- The change does not alter the block/unblock button behavior.
- Inactive instructors remain visible in the new `Inactive` tab and can be unblocked there.
- Added visible instructor counts to the `All`, `Active`, and `Inactive` tab labels.
- Changed instructor row numbering to use ascending sequence numbers instead of the database `instructor_id` values.
- Updated the instructor table header label from `#Id` to `Id`.
- Standardized list table header labels to `Id` on `Admin/Courses.php`, `Admin/index.php`, and `Instructor/Courses.php`.
- Removed the `Add Instructor` button from the `Active` and `Inactive` tabs, keeping it only on the `All` view.
- Fixed duplicate instructor IDs by assigning unique IDs to rows with `instructor_id=0` and adding `AUTO_INCREMENT` / primary key behavior to the `instructor` table.
- Updated the admin instructor UI so a blocked/unblocked instructor is removed immediately from the current `Active` or `Inactive` tab.
