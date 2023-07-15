<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");
	require_once(CORE_PATH."/pokemon/func.php");
	
	function process_trade_request($request_data, $game_region) {
		$decoded_data = decodeExchange($request_data, true, $game_region); // This makes a nice array of data.
		$db = connectMySQL(); // Connect to DION Database!

		// First, delete any existing trades for that user.
		//$stmt = $db->prepare("DELETE IGNORE FROM `pkm_trades` WHERE email = ?;");
		//$stmt->bind_param("s",$decoded_data["email"]);
		//$stmt->execute();

		// Now, begin adding the new trade data...
		$stmt = $db->prepare("INSERT INTO `bxt".$game_region."_exchange` (email, trainer_id, secret_id, offer_gender, offer_species, request_gender, request_species, trainer_name, pokemon_data, mail_data) VALUES (?,?,?,?,?,?,?,?,?,?)");

		// Bind the parameters. REMEMBER: Pokémon Species are the DECIMAL index, not hex!
		$stmt->bind_param("siisisisss",$decoded_data["email"],$decoded_data["trainer_id"],$decoded_data["secret_id"],$decoded_data["offer_gender"],$decoded_data["offer_species"],$decoded_data["req_gender"],$decoded_data["req_species"],$decoded_data["trainer_name"],$decoded_data["pokemon_data"],$decoded_data["mail_data"]);
		$stmt->execute();
	}