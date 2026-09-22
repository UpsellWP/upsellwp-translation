#!/bin/bash

# Author: Team UpsellWP

echo "UpsellWP Translation Pack"

current_dir="$PWD"
plugin_name="upsellwp-translation"

update_ini_file(){
  cd "$current_dir"
  rm -f "i18n/languages/$plugin_name.pot"
  wp i18n make-pot . "i18n/languages/$plugin_name.pot" --slug="$plugin_name" --domain="$plugin_name" --include="$plugin_name.php",collections.php,/src/ --headers='{"Last-Translator":"Flycart <support@flycart.org>","Language-Team":"Flycart <support@flycart.org>"}' --allow-root
  cd "$current_dir"
  echo "Update ini done"
}

copy_folder(){
  cd "$current_dir"
  cd ..
  pack_folder=$PWD"/compressed_pack"
  compress_plugin_folder=$pack_folder/"$plugin_name"
  if [ -d "$pack_folder" ]; then
    rm -r "$pack_folder"
  fi
  mkdir "$pack_folder"
  mkdir "$compress_plugin_folder"
  move_dir=("src" "assets" "i18n" "collections.php" "readme.txt" "$plugin_name.php")
  # shellcheck disable=SC2068
  for dir in ${move_dir[@]}; do
    if [ -e "$current_dir/$dir" ]; then
      cp -r "$current_dir/$dir" "$compress_plugin_folder/$dir"
    fi
  done
  cd "$current_dir"
}

zip_folder(){
  cd "$current_dir"
  cd ..
  pack_compress_folder=$PWD"/compressed_pack"
  cd "$pack_compress_folder"
  rm -f "$plugin_name".zip
  zip -r "$plugin_name".zip "$plugin_name" -q

  if zipinfo "$plugin_name".zip | grep -q "__MACOSX"; then
    zip -d "$plugin_name".zip __MACOSX/\*
  fi

  if zipinfo "$plugin_name".zip | grep -q ".DS_Store"; then
    zip -d "$plugin_name".zip \*/.DS_Store
  fi
  cd "$current_dir"
}

echo "Update ini"
update_ini_file
echo "Copy Folder:"
copy_folder
echo "Zip Folder:"
zip_folder
echo "End"
