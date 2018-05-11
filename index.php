<?php
	// LISTE DES CONFIGUARION DU FRAMEWORK
	//Ceci est un autre commentaire
	require_once './core/config.php';

	$request = new \ESMT\PHARMALIV\Request;
	$pArray = explode( '/', $request->relPath() );

	$section = $pArray[0];
	unset($pArray[0]);

	// Page d'accueil du site
	if ( $section == '' ) {
        (new Response())->render ( 'home.html', $request->get() );

	// ON REGARDE SI LA SECTION DEMANDEE EXISTE
	} else if ( in_array($section, $sections) ){

		// ON RECUPERE LA NOUVELLE URL DE LA SECTION
		$url = '/'.implode('/', $pArray);
		require './src/'.$section.'/urls.php';

		$found = false;

		// ON REGARDE SI LURL FAIT PARTIE DE LA LISTE DES URLS
		// ENUMEREES
		foreach ($urls as $path => $mtd) {
			$pattern = createPattern( $path );

			if ( preg_match( $pattern, $url, $params ) ){
				unset($params[0]);
				$found = true;
				$method = $mtd;
				break;
			}
		}

		// SI ON TROUVE UNE CORRESPONDANCE, ON RECUPERE LA METHODE
		// ACTION ET ON L'APPELLE
		if ( $found ) {
			require './src/'.$section.'/actions.php';

			$response = new Response;
			$response->addTemplateDir ( './src/'.$section.'/templates' );

			$action = new \ESMT\PHARMALIV\Action ( $request, $response );

			if (count($params) > 0){
				$method( $action ,$params );

			} else {
				$method ( $action );
			}

		// SI L'URL N'APPARTIENT PAS A LA SECTION
		} else {
            (new Response())->render ( '404.html' );
		}

	// SI LA SECTION N'EXISTE PAS ON AFFICHE UNE ERREUR
	} else {
        (new Response())->render ( '404.html' );
	}