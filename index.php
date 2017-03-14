<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
* MongoDB Adapter for CRUD
* MongoDB Extension Version 1.2.3 
*/


class Mongo {
	

		/*
		|-----------------------------------------
		| Database credentials
		|-----------------------------------------
		| It is good to set explicitly or using config file instead of using setter method
		| So, it will cut down method calls and conditions, in concern to performance
		|-----------------------------------------
		*/		
		protected $connection;
		protected $host = 'localhost';
		protected $port = 27017;
		protected $username;
		protected $password;
		protected $database;

		

		public function __construct(){

			$this->mongo_connect();
			
		}



		// Connect to MongoDB
		private function mongo_connect(){

			require 'vendor/autoload.php';
			try{
				$this->connection = new MongoDB\Client('mongodb://'.$this->host.':'.$this->port);
			}catch(Exception $error){
				throw new Exception($error);
			}

		}



		// Setter for database name to be used
		public function set_database($name){

			if(!empty($name)){
				$this->database = $name;
			}else{
				return false;
			}

		}



		// Prepare collection to be used
		public function set_collection($name){

			if(!empty($name)){
				$this->collection = $name;
			}else{
				return false;
			}	

		}



		/*
		| Prepare collection binding
		| Bind $connection->$database->$collection
		*/
		public function prep_document(){
			$database 	= $this->database;
			$collection = $this->collection;
			return $this->connection->$database->$collection;
		}



		/*
		| Insert one document
		| param : must be array, multidimensional array supported
		| return : single inserted id
		*/
		public function insert_document(array $arg){

			if(is_array($arg)){
				$prepare = $this->prep_document();
				$insert = $prepare->insertOne($arg);
				return $insert->getInsertedId();
			}else{				
				die('data sould be of array type');
			}

		}



		/*
		| Insert bulk documents
		| param : must be array, multidimensional array supported
		| return : array of inserted ids
		*/
		public function insert_documents(array $arg){

			if(is_array($arg)){
				$prepare = $this->prep_document();
				$insert = $prepare->insertMany($arg);
				return $insert->getInsertedIds();
			}else{				
				die('data sould be of array type');
			}

		}


		/*
		| Find data from database by ID
		| param : key (id) string
		| return : json data
		*/
		public function find_data_by_id($arg){
			$prepare = $this->prep_document();
			return $prepare->findOne(["_id" => new MongoDB\BSON\ObjectID($arg)]);
		}



		/*
		| Find data from database
		| Search with KEY => VALUE
		| param : array('key' => 'value')
		| return : json data (only 1 document)
		*/
		public function find_data(array $arg){
			$prepare = $this->prep_document();
			return $prepare->findOne($arg);
		}



		/*
		| Find multiple data from database
		| Search with KEY => VALUE
		| param : array('key' => 'value', 'key' => 'value'......)
		| return : json data
		*/
		public function find_all(array $arg){
			$prepare = $this->prep_document();
			return $prepare->find($arg);
		}





}



$mdb = new Mongo();
$mdb->set_database('shop');
$mdb->set_collection('products');
/*$multiple = $mdb->insert_documents([
				[
				    'username' => 'admin1',
				    'email' => 'admin@example.com',
				    'name' => 'Admin User',
				    'date' => '25.08.1987',
				],
				[
				    'username' => 'admin2',
				    'email' => 'admin@example.com',
				    'name' => 'Admin User',
				    'date' => '25.08.1987',
				]
			]);*/
			
$data = $mdb->find_all(['username' => 'admin1', 'email' => 'admin@example.com']);

echo '<pre>';
print_r($data);