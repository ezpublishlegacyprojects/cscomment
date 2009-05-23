#?ini charset="iso-8859-1"?
# eZ Publish configuration file for date and time handling.
#
# NOTE: It is not recommended to edit this files directly, instead
#       a file in override should be created for setting the
#       values that is required for your site. Either create
#       a file called settings/override/datetime.ini.append or
#       settings/override/datetime.ini.append.php for more security
#       in non-virtualhost modes (the .php file may already be present
#       and can be used for this purpose).

[ClassSettings]
Formats[commentdate]=%Y-%m-%d
