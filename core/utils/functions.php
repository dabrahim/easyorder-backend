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