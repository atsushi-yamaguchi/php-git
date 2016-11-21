<?php
define('ROOT_DIR', __DIR__ . '/');

/**
 * 指定したファイルをzlib圧縮してblobオブジェクトを記録する
 * ファイル名: .git/objects/2e/fdd7fc4e9ce563c7b88b724c437656b9a15939
 * 内容: blob filesize\0content
 *
 * @param string $filename
 * @return string
 */
function hash_object($filename)
{
  $filesize = filesize($filename);
  $content = file_get_contents($filename);
  
  $blob = "blob $filesize\0$content";
  
  $sha1 = sha1($blob);
  
  preg_match("#([\da-f]{2})([\da-f]{38})#", $sha1, $match);
  
  $hash_object_dir = ROOT_DIR . '.git/objects/' . $match[1] . '/';
  $hash_object_name = $hash_object_dir . $match[2];
  
  if ( ! file_exists($hash_object_dir)) {
    mkdir($hash_object_dir, 0777, TRUE);
  }
  
  # zlib圧縮する 
  $hash_object_content = gzcompress($blob);
 
  # .git/objects/2e/fdd7fc4e9ce563c7b88b724c437656b9a15939 へblobオブジェクトを保存する 
  file_put_contents($hash_object_name, $hash_object_content);
  return $sha1;
}

echo hash_object($argv[1]);

