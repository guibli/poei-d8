git checkout master
git pull
cp  config_export/* config_sync/
# rm config_export/*
drush cim sync  -y
drush fra -y
drush updb -y
