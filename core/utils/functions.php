<?php
	/*function render ( $template, $data = null ) {
		require '/core/sections.php';

		$templateDirs = array( './templates' );
		foreach ( $sections as $section ) {
			$templateDirs[] = './src/'.$section.'/templates';
		}

		require_once '/vendor/autoload.php';

		if ($data == null){
			$data = array();
		}

		$loader = new Twig_Loader_Filesystem( $templateDirs );
		$twig = new Twig_Environment( $loader );

		$data['rootDir'] = dirname($_SERVER['PHP_SELF']);
		
		echo $twig->render( $template, $data );
	}*/

	use \Firebase\JWT\JWT;

	function createPattern ( $str ) {
		return '#^'.preg_replace('({:})', '([a-zA-Z0-9]+)', $str).'$#';
	}

    function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for($i=0; $i<$length; $i++) {
            $str .= $keyspace[rand(0, $max)];
        }
        return $str;
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

        function saveFile (array $file, $targetDir, array $authorizedExtensions, $isImage = false, $maxSize = 2000000) {
        if ( $file['size'] < $maxSize ) {
            if ( $isImage && !getimagesize($file['tmp_name'])) {
                throw new Exception("Le fichier reçu n'est pas une image");
            }
            $fileExtension = strtolower(pathinfo(basename($file['name']), PATHINFO_EXTENSION));
            if ( in_array($fileExtension, $authorizedExtensions) ){
                $outputName = random_str(35) . '.' . $fileExtension;
                $targetFile = $targetDir . $outputName;
                if (move_uploaded_file($file['tmp_name'], $targetFile)){
                    return $outputName;
                } else {
                    throw new Exception("L'enregistrement du fichier a échoué !");
                }
            } else {
                throw new Exception("L'extension du fichier n'est pas correcte");
            }
        } else {
            throw new Exception("La taille du fichier doit être inférieure à ".$maxSize." kb");
        }
    }

    function getToken () {
        $token = null;
        $headers = apache_request_headers();

        if(isset($headers['Authorization'])){
            $token = explode(' ', $headers['Authorization'])[1];
            return $token;
        } else {
            return null;
        }
    }

    function decodeToken ( $jwt ) {
        $key = "5wu{@N\"i!^G>M5z0Zzk,e8,w1G$5[#";
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        return (array) $decoded;
    }

    function getTokenData () {
        $token = getToken();
        if ($token == null) {
            throw new Exception("Le token n'a pas été correctement inséré dans l'entête HTTP");
        }
        $data = decodeToken( $token );
        return $data;
    }

    function toString (array $array) {
        $tmp = array();
        foreach ($array as $key => $value) {
            $tmp[] = $key . "=" . $value;
        }
        $output = "[" . implode(", ", $tmp) . "]";
        return $output;
    }

    function saveIntoLogs ($response) {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $currentDateTime = date("d-m-Y, H\h i\m\\n s\s");

        if ($method == 'GET'){
            $data = toString( $_GET );
        } else if ($method == 'POST'){
            $data = toString( $_POST );
        }

        $log = "Request: ".$method." ". $uri ." ". $data . " " . $currentDateTime. " ---> " .$response;
        $file = fopen("./core/logs.txt", 'a');
        fwrite($file, $log . PHP_EOL);
    }