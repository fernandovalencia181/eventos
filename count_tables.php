
$tables_list = DB::select('SHOW TABLES'); 
foreach ($tables_list as $t) {
    // $t is an object with one property name based on 'Tables_in_eventos2' or similar
    // We convert it to array to extract first value
    $arr = (array)$t;
    $tableName = reset($arr);
    
    // Check if table exists to be safe and avoid errors (DB::table works directly)
    try {
        $count = DB::table($tableName)->count();
        echo "Table: " . $tableName . " - Count: " . $count . "\n";
    } catch (\Exception $e) {
        // Just ignore errors
    }
}
exit();
