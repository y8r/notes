STATUS OF BRN REPOSITORY
========================
This is a document to keep track of which repositories use the new process
to deploy through merging (svn) vs the legacy deploy method.  To use the new 
process you will need to have versions of each branch (including the trunk) 
checked out to your machine.

The naming conventions should be as follows.  We will use the core repository as
an example:
- core (trunk)
- core-lx001 (ITG)
- core-lx002 (CAT)
- core-lx003|lx004 (PROD)

For drupal please use the following:
- drupal (trunk)
- drupal-lx317 (CAT)
- drupal-lx324|lx325 (PROD)

You can create a folder in cornerstone to keep these working copies organized.
Please see a ux developer if you have any questions.

Deploy through merging (svn)
----------------------------
To promote the following list of repositories please do so by merging to the
branch (environment) that you wish to deploy to:

- core
- drupal
- dtc (supported up to CAT)
- laravel
- mutualofomahaebank
- mutualplans

Legacy Deploy (FTP)
-------------------
To promote all other repositories please use FTP to promote to the environment
you with to deploy to.  Don't forget to promote to lx001.
