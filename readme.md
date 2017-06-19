// INSERT QUERIES
//
// $DB->insert('mytable')->data(array('title' => 'My title', 'name' => 'My name'));
// Produces: INSERT INTO mytable (title, name) VALUES ('My title', 'My name')
//
// UPDATE QUERIES
//
//
// $DB->update('mytable')->set(array('name' => 'New name'))->limit(1)->result();
// Produces:  UPDATE `mytable` SET `name` = 'New name' LIMIT 1
//
// $DB->update('mytable')->set(array('name' => 'New name'))->where(array('id' => 1))->result();
// Produces:  UPDATE `mytable` SET `name` = 'New name' WHERE id = `1`
//
// SELECT QUERIES
//
//
// $DB->select('title, content')->from('mytable')->result();
// Produces: SELECT title, content FROM `mytable`
//
// $DB->select('*')->from('mytable')->where(array('name' => 'My name', 'title' => 'My title'));
// Produces: SELECT * `mytable` WHERE 'name' = 'My name' AND 'title' ='My title'
//
// $DB->select('*')->from('mytable')->join('test', array('test.id' => 'mytable.id_test'), 'left')->result();
// Produces: SELECT * FROM `mytable` LEFT JOIN test ON test.id = mytable.id_test
//
// $DB->select('*')->from('mytable')-group_by('name')->result();
// Produces: SELECT * `mytable` GROUP BY name
//
// $DB->select('*')->from('mytable')->order_by('id', 'DESC')->result();
// Produces: SELECT * `mytable` ORDER BY 'id' DESC
//
// $DB->select('*')->from('mytable')-limit(10)->result();
// Produces: SELECT * `mytable` LIMIT 10
//
// $DB->select('*')->from('mytable')-limit(10, 0)->result();
// Produces: SELECT * `mytable` LIMIT 0, 10
//
//
// RESULT METHODS FOR SELECT QUERIES:
//
// result()
//
// 	$DB->select('id')->from('mytable')->result();
// 	would execute and return an objects array of results of the query:
// 	array (size=2)
//  	0 => 
//    	object(stdClass)[5]
//      	public 'id' => string '1' (length=2)
//    
//  	1 => 
//    	object(stdClass)[6]
//      	public 'id' => string '2' (length=2)
//
//
// result_array()
//
// 	$DB->select('id')->from('mytable')->result_array();
// 	would execute and return an array of results of the query:
//	array (size=15)
//  		0 => 
//    		array (size=1)
// 
//     			'id' => string '1' (length=1)
//  		1 => 
//   		array (size=1)
//      		'id' => string '2' (length=1)
// row()
//
// 	$DB->select('id')->from('mytable')-row();
// 	Will execute and return the first element of the query results array
//	array (size=15)
//  		0 => 
//    		array (size=1)
// 
//     			'id' => string '1' (length=1)
// 
//
//
// COMPILED QUERIES
//
// Methods get_compiled_insert(), get_compiled_update(), get_compiled_select() return a query string without execution it
