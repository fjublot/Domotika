<?php
	class database extends model
	{
		/*******************************/
		/* PARTIE DES REQUETES SENDEDS */
		/*******************************/

		/**
		 * Récupère les SMS envoyés depuis une date
		 * @param $date : La date depuis laquelle on veux les SMS (au format 2014-10-25)
		 * @return array : Tableau avec tous les SMS depuis la date
		 */
		public function getNbSendedsSinceGroupDay($date)
		{
			$query = "
				SELECT COUNT(id) as nb, DATE_FORMAT(at, '%Y-%m-%d') as at_ymd
				FROM sendeds
				WHERE at > STR_TO_DATE(:date, '%Y-%m-%d')
				GROUP BY at_ymd
			";

			$params = array(
				'date' => $date,
			);

			return $this->runQuery($query, $params);
		}

		/**
		 * Récupère SMS envoyé à partir de son id
		 * @param int $id = L'id du SMS
		 * @return array : Retourne le SMS
		 */
		public function getSendedFromId($id)
		{
			$query = "
				SELECT *
				FROM sendeds
				WHERE id = :id";
		
			$params = array(
				'id' => $id
			);

			return $this->runQuery($query, $params);
		}

		/**
		 * Insert un nouveau SMS envoyé
		 * @param string $date : La date d'envoie du message
		 * @param string $target : Numéro auquel a été envoyé le message
		 * @param string $content : Texte du message
		 * @return int : le nombre de SMS créés
		 */
		public function createSended($date, $target, $content)
		{
			$query = '
				INSERT INTO sendeds(at, target, content)
				VALUES (:date, :target, :content)
			';

			$params = array(
				'date' => $date,
				'target' => $target,
				'content' => $content,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT); //On retourne le nombre de lignes ajoutés
		}

		/**
		 * update un sended
		 * @param int $id : L'id du sended à modifier
		 * @param string $date : La nouvelle date du SMS
		 * @param string $target : La nouvelle cible du SMS
		 * @param string $content : Le nouveau contenu du SMS
		 * @param string $success : Le nouveau statut du SMS
		 * @return void
		 */
		public function updateSended($id, $date, $target, $content)
		{
			$query = '
				UPDATE sendeds
				SET at = :date,
				target = :target,
				content = :content,
				WHERE id = :id
			';

			$params = array(
				'id' => $id,
				'date' => $date,
				'target' => $target,
				'content' => $content,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/*********************************/
		/* PARTIE DES REQUETES RECEIVEDS */
		/*********************************/

		/**
		 * Insert un nouveau SMS reçu
		 * @param string $date : La date d'envoie du message
		 * @param string $send_by : Numéro auquel depuis lequel le message a ete envoye
		 * @param string $content : Texte du message
		 * @param string $is_command : Commande reconnue
		 * @return int : le nombre de SMS créés
		 */
		public function createReceived($date, $send_by, $content, $is_command)
		{
			$query = '
				INSERT INTO receiveds(at, send_by, content, is_command)
				VALUES (:date, :send_by, :content, :is_command)
			';

			$params = array(
				'date' => $date,
				'send_by' => $send_by,
				'content' => $content,
				'is_command' => $is_command,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT); //On retourne le nombre de lignes ajoutés
		}

		/**
		 * Récupère les SMS reçus depuis une date
		 * @param $date : La date depuis laquelle on veux les SMS (au format 2014-10-25)
		 * @return array : Tableau avec tous les SMS depuis la date
		 */
		public function getNbReceivedsSinceGroupDay($date)
		{
			$query = "
				SELECT COUNT(id) as nb, DATE_FORMAT(at, '%Y-%m-%d') as at_ymd
				FROM receiveds
				WHERE at > STR_TO_DATE(:date, '%Y-%m-%d')
				GROUP BY at_ymd
			";

			$params = array(
				'date' => $date,
			);

			return $this->runQuery($query, $params);
		}

		/******************************/
		/* PARTIE DES REQUETES EVENTS */
		/******************************/

		/**
		 * Récupère les evenements enregistrés depuis une date
		 * @param $date : La date depuis laquelle on veux les evenements (au format 2014-10-25)
		 * @return array : Tableau avec tous les evenements depuis la date
		 */
		public function getEventsSince($date)
		{
			$query = "
				SELECT *
				FROM events
				WHERE at > STR_TO_DATE(:date, '%Y-%m-%d')
			";

			$params = array(
				'date' => $date,
			);

			return $this->runQuery($query, $params);
		}

		/**
		 * Enregistre un nouvel évenement
		 * @param string $type = Type d'evenement enregistré
		 * @param string $text = Texte de l'évenement (max 255chars)
		 * @return int = Nombre de lignes insérées
		 */
		public function createEvent($type = '', $text = '')
		{
			$query = '
				INSERT INTO events(type, at, text)
				VALUES (:type, NOW(), :text)
			';

			$params = array(
				'type' => $type,
				'text' => $text
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/********************************/
		/* PARTIE DES REQUETES CONTACTS */
		/********************************/
		
		/**
		 * Supprime tous les contacts dont l'id fait partie du tableau fourni
		 * @param $contacts_ids : Tableau des id des contacts à supprimer
		 * @return int : Nombre de lignes supprimées
		 */
		public function deleteContactsIn($contacts_ids)
		{
			$query = "
				DELETE FROM contacts
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($contacts_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			$req = $this->bdd->prepare($query);
			$req->execute($params);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Compte le nombre de contacts
		 * @return int : le nombre de contacts
		 */
		public function countContacts()
		{
			$query = '
				SELECT COUNT(id) as nb
				FROM contacts
			';

			$params = array();

			$donnees = $this->runQuery($query, $params, model::FETCH);
			return $donnees['nb'];
		}

		/**
		 * Insert un contact
		 * @param string $nom : Le nom du nouveau contact
		 * @param string $number : Le numéro du nouveau contact
		 * @return int : le nombre de contacts
		 */
		public function createContact($name, $number)
		{
			$query = '
				INSERT INTO contacts(name, number)
				VALUES (:name, :number)
			';

			$params = array(
				'name' => $name,
				'number' => $number,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Récupère un contact à partir de son nom
		 * @param string $name = Le nom du contact
		 * @return array : Retourne le contact
		 */
		public function getContactFromName($name)
		{
			$query = "
				SELECT *
				FROM contacts
				WHERE name = :name";
		
			$params = array(
				'name' => $name
			);

			return $this->runQuery($query, $params, model::FETCH);
		}

		/**
		 * Récupère les contacts dont l'id fait partie de la liste fournie
		 * @param array $contacts_ids = Tableau des id des contacts voulus
		 * @return array : Retourne un tableau avec les contacts adaptés
		 */
		public function getContactsIn($contacts_ids)
		{
			$query = "
				SELECT *
				FROM contacts
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($contacts_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params);
		}

		/**
		 * update un contact
		 * @param int $id : L'id du contact à modifier
		 * @param string $name : Le nouveau nom du contact
		 * @param string $number : Le nouveau numéro du contact
		 * @return void
		 */
		public function updateContact($id, $name, $number)
		{
			$query = '
				UPDATE contacts
				SET name = :name,
				number = :number
				WHERE id = :id
			';

			$params = array(
				'id' => $id,
				'name' => $name,
				'number' => $number,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/******************************/
		/* PARTIE DES REQUETES GROUPS */
		/******************************/

		/**
		 * Récupère un group à partir de son nom
		 * @param string $name = Le nom du group
		 * @return array : Retourne le group
		 */
		public function getGroupFromName($name)
		{
			$query = "
				SELECT *
				FROM groups
				WHERE name = :name";
		
			$params = array(
				'name' => $name
			);

			return $this->runQuery($query, $params, model::FETCH);
		}

		/**
		 * Insert un group
		 * @param string $nom : Le nom du nouveau group
		 * @return int : le nombre de lignes crées
		 */
		public function createGroup($name)
		{
			$query = '
				INSERT INTO groups(name)
				VALUES (:name)
			';

			$params = array(
				'name' => $name,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * update un groupe
		 * @param int $id : L'id du groupe à modifier
		 * @param string $name : Le nouveau nom du groupe
		 * @return int : Le nombre de lignes modifiés
		 */
		public function updateGroup($id, $name)
		{
			$query = '
				UPDATE groups
				SET name = :name
				WHERE id = :id
			';

			$params = array(
				'id' => $id,
				'name' => $name,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Compte le nombre de groups
		 * @return int : le nombre de groups
		 */
		public function countGroups()
		{
			$query = '
				SELECT COUNT(id) as nb
				FROM groups
			';

			$params = array();

			$donnees = $this->runQuery($query, $params, model::FETCH);
			return $donnees['nb'];
		}

		/**
		 * Récupère les groupes dont l'id fait partie de la liste fournie
		 * @param array $groups_ids = Tableau des id des groupes voulus
		 * @return array : Retourne un tableau avec les groupes adaptés
		 */
		public function getGroupsIn($groups_ids)
		{
			$query = "
				SELECT *
				FROM groups
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($groups_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params);
		}

		/**
		 * Supprime tous les groupes dont l'id fait partie du tableau fourni
		 * @param $contacts_ids : Tableau des id des groups à supprimer
		 * @return int : Nombre de lignes supprimées
		 */
		public function deleteGroupsIn($groups_ids)
		{
			$query = "
				DELETE FROM groups
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($groups_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/***************************************/
		/* PARTIE DES REQUETES GROUPS_CONTACTS */
		/***************************************/

		/**
		 * Retourne tous les contacts pour un groupe donnée
		 * @param int $id_group : L'id du groupe
		 * @return array : Tous les contacts compris dans le groupe
		 */
		public function getContactsForGroup($id_group)
		{
			$query = '
				SELECT con.id as id, con.name as name, con.number as number
				FROM groups_contacts as g_c
				JOIN contacts as con
				ON (g_c.id_contact = con.id)
				WHERE(g_c.id_group = :id_group)
			';

			$params = array(
				'id_group' => $id_group,
			);

			return $this->runQuery($query, $params);
		}

		/* Insert un groups_contacts
		 * @param string $id_group : L'id du group
		 * @param string $id_contact : L'id du contact
		 * @return int : le nombre de lignes crées
		 */
		public function createGroups_contacts($id_group, $id_contact)
		{
			$query = '
				INSERT INTO groups_contacts(id_group, id_contact)
				VALUES (:id_group, :id_contact)
			';

			$params = array(
				'id_group' => $id_group,
				'id_contact' => $id_contact,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Récupère tout les groupes, avec le nombre de contact dans chacun
		 * @return array : Tableau avec tous les groupes et le nombre de contacts liés
		 */
		public function getGroupsWithContactsNb($order_by = '', $desc = false, $limit = false, $offset = false)
		{
			$query = "
				SELECT gro.id as id, gro.name as name, COUNT(g_c.id) as nb_contacts
				FROM groups as gro
				LEFT JOIN groups_contacts as g_c
				ON (g_c.id_group = gro.id)
				GROUP BY id
			";

			if ($order_by)
			{
				if($this->fieldExist($order_by, 'contacts'))
				{
					$query .= ' ORDER BY '. $order_by;
					if ($desc) 
					{
						$query .= ' DESC';
					}
				}
			}

			if ($limit !== false)
			{
				$query .= ' LIMIT :limit';
				if ($offset !== false)
				{
					$query .= ' OFFSET :offset';
				}
			}

			$req = $this->bdd->prepare($query);

			if ($limit !== false)
			{
				$req->bindParam(':limit', $limit, model::PARAM_INT);
				if ($offset !== false)
				{
					$req->bindParam(':offset', $offset, model::PARAM_INT);
				}
			}

			$req->execute();
			return $req->fetchAll();
		}

		/**
		 * Supprime tous les groups_contacts pour un groupe donné
		 * @param int $id : L'id du groupe pour lequel on doit supprimer les groups_contacts
		 * @return int Le nombre de lignes supprimées
		 */
		public function deleteGroups_contactsForGroup($id_group)
		{
			$query = '
				DELETE FROM groups_contacts
				WHERE id_group = :id_group
			';

			$params = array(
				'id_group' => $id_group
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**********************************/
		/* PARTIE DES REQUETES SCHEDULEDS */
		/**********************************/

		/**
		 * Récupère SMS programmé à partir de son id
		 * @param int $id = L'id du SMS programmé
		 * @return array : Retourne le contact
		 */
		public function getScheduledFromId($id)
		{
			$query = "
				SELECT *
				FROM scheduleds
				WHERE id = :id";
		
			$params = array(
				'id' => $id
			);

			return $this->runQuery($query, $params, model::FETCH);
		}

		/**
		 * Récupère les sms programmés dont l'id fait partie de la liste fournie
		 * @param array $scheduleds_ids = Tableau des id des sms voulus
		 * @return array : Retourne un tableau avec les groupes adaptés
		 */
		public function getScheduledsIn($scheduleds_ids)
		{
			$query = "
				SELECT *
				FROM scheduleds
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($scheduleds_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params);
		}

		/**
		 * Récupère tout les sms programmés non en cours, et dont la date d'envoie inférieure à celle renseignée
		 * @param string $date : Date avant laquelle on veux les sms
		 * @return array : Tableau avec les sms programmés demandés
		 */
		public function getScheduledsNotInProgressBefore($date)
		{
			$query = "
				SELECT *
				FROM scheduleds
				WHERE progress = 0
				AND at <= :date
			";

			$params = array(
				'date' => $date,
			);

			return $this->runQuery($query, $params);
		}

		/**
		 * Insert un sms
		 * @param string $date : La date d'envoie du SMS
		 * @param string $content : Le contenu du SMS
		 * @return int : le nombre de lignes crées
		 */
		public function createScheduleds($date, $content)
		{
			$query = '
				INSERT INTO scheduleds(at, content, progress)
				VALUES (:date, :content, :progress)
			';

			$params = array(
				'date' => $date,
				'content' => $content,
				'progress' => false,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * update un sms programmé
		 * @param int $id : L'id du sms à modifier
		 * @param string $date : La nouvelle date du sms
		 * @param string $content : Le nouveau contenu du sms
		 * @param boolean $progress : Le nouveau statut de la progression du sms
		 * @return int : Le nombre de lignes modifiées
		 */
		public function updateScheduled($id, $date, $content, $progress)
		{
			$query = '
				UPDATE scheduleds
				SET at = :date,
				content = :content,
				progress = :progress
				WHERE id = :id
			';

			$params = array(
				'id' => $id,
				'date' => $date,
				'content' => $content,
				'progress' => $progress,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Supprime tous les sms programmés dont l'id fait partie du tableau fourni
		 * @param $contacts_ids : Tableau des id des sms à supprimer
		 * @return int : Nombre de lignes supprimées
		 */
		public function deleteScheduledsIn($scheduleds_ids)
		{
			$query = "
				DELETE FROM scheduleds
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($scheduleds_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Compte le nombre de sms programmés
		 * @return int : le nombre de sms programmés
		 */
		public function countScheduleds()
		{
			$query = '
				SELECT COUNT(id) as nb
				FROM scheduleds
			';

			$params = array();

			$donnees = $this->runQuery($query, $params, model::FETCH);
			return $donnees['nb'];
		}

		/********************************/
		/* PARTIE DES REQUETES COMMANDS */
		/********************************/

		/**
		 * Récupère les commands dont l'id fait partie de la liste fournie
		 * @param array $commands_ids = Tableau des id des commands voulus
		 * @return array : Retourne un tableau avec les commands adaptés
		 */
		public function getCommandsIn($commands_ids)
		{
			$query = "
				SELECT *
				FROM commands
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($commands_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params);
		}

		/**
		 * Insert un commande
		 * @param string $name : Le nom de la command
		 * @param string $script : Le chemin du script à appeler
		 * @param string $admin : Défini si il est nécessaire d'être admin
		 * @return int : le nombre de commandes ajoutées
		 */
		public function createCommand($name, $script, $admin)
		{
			$query = '
				INSERT INTO commands(name, script, admin)
				VALUES (:name, :script, :admin)
			';

			$params = array(
				'name' => $name,
				'script' => $script,
				'admin' => $admin,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * update une commande
		 * @param int $id : L'id de la commande à modifier
		 * @param string $name : Le nouveau nom de la commande
		 * @param string $script : Le nouveau script de la commande
		 * @param string $name : Nouvel état de la necessité des droits administrateur
		 * @return int : Le nombre de lignes modifiée
		 */
		public function updateCommand($id, $name, $script, $admin)
		{
			$query = '
				UPDATE commands
				SET name = :name,
				script = :script,
				admin = :admin
				WHERE id = :id
			';

			$params = array(
				'id' => $id,
				'name' => $name,
				'script' => $script,
				'admin' => $admin,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Supprime tous les commands dont l'id fait partie du tableau fourni
		 * @param $commands_ids : Tableau des id des commands à supprimer
		 * @return int : Nombre de lignes supprimées
		 */
		public function deleteCommandsIn($commands_ids)
		{
			$query = "
				DELETE FROM commands
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($commands_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Compte le nombre de commands
		 * @return int : le nombre de commands
		 */
		public function countCommands()
		{
			$query = '
				SELECT COUNT(id) as nb
				FROM commands
			';

			$params = array();

			$donnees = $this->runQuery($query, $params, model::FETCH);
			return $donnees['nb'];
		}

		/*******************************************/
		/* PARTIE DES REQUETES SCHEDULEDS_CONTACTS */
		/*******************************************/
		
		/**
		 * Créer un nouveau scheduleds_contacts
		 * @param int $id_scheduled : L'id du SMS programmé lié
		 * @param int $id_contact : L'id du contact lié
		 * @return int : Le nombre de lignes insérées
		 */
		public function createScheduleds_contacts($id_scheduled, $id_contact)
		{
			$query = '
				INSERT INTO scheduleds_contacts(id_scheduled, id_contact)
				VALUES (:id_scheduled, :id_contact)
			';

			$params = array(
				'id_scheduled' => $id_scheduled,
				'id_contact' => $id_contact,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Retourne tous les contacts pour un sms programmé donnée
		 * @param int $id_sms : L'id du sms
		 * @return array : Tous les contacts compris dans le schedulede
		 */
		public function getContactsForScheduled($id_scheduled)
		{
			$query = '
				SELECT con.id as id, con.name as name, con.number as number
				FROM scheduleds_contacts as s_c
				JOIN contacts as con
				ON (s_c.id_contact = con.id)
				WHERE(s_c.id_scheduled = :id_scheduled)
			';

			$params = array(
				'id_scheduled' => $id_scheduled,
			);

			return $this->runQuery($query, $params);
		}

		/**
		 * Supprime tous les scheduleds_contacts pour un sms donné
		 * @param int $id_scheduled : L'id du sms pour lequel on doit supprimer les scheduleds_contacts
		 * @return int Le nombre de lignes supprimées
		 */
		public function deleteScheduleds_contactsForScheduled($id_scheduled)
		{
			$query = '
				DELETE FROM scheduleds_contacts
				WHERE id_scheduled = :id_scheduled
			';

			$params = array(
				'id_scheduled' => $id_scheduled
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Change le statut des scheduleds dont l'id est fourni dans $scheduleds_id
		 * @param array $scheduleds_ids = Tableau des id des sms voulus
		 * @return int : Retourne le nombre de lignes mises à jour
		 */
		public function updateProgressScheduledsIn($scheduleds_ids, $progress)
		{
			$query = "
				UPDATE scheduleds
				SET progress = :progress
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($scheduleds_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];
			$params['progress'] = (boolean)$progress;

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/******************************************/
		/* PARTIE DES REQUETES SCHEDULEDS_NUMBERS */
		/******************************************/
		
		/**
		 * Créer un nouveau scheduleds_numbers
		 * @param int $id_scheduled : L'id du SMS programmé lié
		 * @param string $number : Le numéro de téléphone lié
		 * @return int : Le nombre de lignes insérées
		 */
		public function createScheduleds_numbers($id_scheduled, $number)
		{
			$query = '
				INSERT INTO scheduleds_numbers(id_scheduled, number)
				VALUES (:id_scheduled, :number)
			';

			$params = array(
				'id_scheduled' => $id_scheduled,
				'number' => $number,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Supprime tous les scheduleds_numbers pour un sms donné
		 * @param int $id_scheduled : L'id du sms pour lequel on doit supprimer les scheduleds_numbers
		 * @return int Le nombre de lignes supprimées
		 */
		public function deleteScheduleds_numbersForScheduled($id_scheduled)
		{
			$query = '
				DELETE FROM scheduleds_numbers
				WHERE id_scheduled = :id_scheduled
			';

			$params = array(
				'id_scheduled' => $id_scheduled
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Retourne tous les numéros pour un scheduled donné
		 * @param int $id_scheduled : L'id du scheduled
		 * @return array : Tous les numéro compris dans le scheduled
		 */
		public function getNumbersForScheduled($id_scheduled)
		{
			$query = '
				SELECT *
				FROM scheduleds_numbers
				WHERE id_scheduled = :id_scheduled
			';

			$params = array(
				'id_scheduled' => $id_scheduled,
			);

			return $this->runQuery($query, $params);
		}

		/*****************************************/
		/* PARTIE DES REQUETES SCHEDULEDS_GROUPS */
		/*****************************************/
		
		/**
		 * Créer un nouveau scheduleds_groups
		 * @param int $id_scheduled : L'id du SMS programmé lié
		 * @param int $id_group : L'id du group lié
		 * @return int : Le nombre de lignes insérées
		 */
		public function createScheduleds_groups($id_scheduled, $id_group)
		{
			$query = '
				INSERT INTO scheduleds_groups(id_scheduled, id_group)
				VALUES (:id_scheduled, :id_group)
			';

			$params = array(
				'id_scheduled' => $id_scheduled,
				'id_group' => $id_group,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Supprime tous les scheduleds_groups pour un sms donné
		 * @param int $id_scheduled : L'id du sms pour lequel on doit supprimer les scheduleds_groups
		 * @return int Le nombre de lignes supprimées
		 */
		public function deleteScheduleds_groupsForScheduled($id_scheduled)
		{
			$query = '
				DELETE FROM scheduleds_groups
				WHERE id_scheduled = :id_scheduled
			';

			$params = array(
				'id_scheduled' => $id_scheduled
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Retourne tous les groupes pour un scheduled donnée
		 * @param int $id_scheduled : L'id du schedulede
		 * @return array : Tous les groupes compris dans le scheduled
		 */
		public function getGroupsForScheduled($id_scheduled)
		{
			$query = '
				SELECT gro.id as id, gro.name as name
				FROM scheduleds_groups as s_g
				JOIN groups as gro
				ON (s_g.id_group = gro.id)
				WHERE(s_g.id_scheduled = :id_scheduled)
			';

			$params = array(
				'id_scheduled' => $id_scheduled,
			);

			return $this->runQuery($query, $params);
		}

		/*****************************/
		/* PARTIE DES REQUETES USERS */
		/*****************************/
	
		/**
		 * Récupère un utilisateur à partir de son email
		 * @param string $email = L'email de l'utilisateur
		 * @return array : Retourne l'utilisateur
		 */
		public function getUserFromEmail($email)
		{
			$query = "
				SELECT *
				FROM users
				WHERE email = :email";
		
			$params = array(
				'email' => $email
			);

			$req = $this->bdd->prepare($query);
			$req->execute($params);

			return $this->runQuery($query, $params, model::FETCH);
		}

		/**
		 * update un user
		 * @param int $id : L'id du user à modifier
		 * @param string $email : Le nouveau email du user
		 * @param string $password : Le nouveau password du user
		 * @param string $admin : Le nouveau statut admin du user
		 * @return void
		 */
		public function updateUser($id, $email, $password, $admin)
		{
			$query = '
				UPDATE users
				SET email = :email,
				password = :password,
				admin = :admin
				WHERE id = :id
			';

			$params = array(
				'id' => $id,
				'email' => $email,
				'password' => $password,
				'admin' => $admin,
			);

			$req = $this->bdd->prepare($query);
			$req->execute($params);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Insert un utilisateur
		 * @param string $email : L'adresse email du nouvel utilisateur
		 * @param string $password : Le mot de passe de l'utilisateur
		 * @param boolean $admin : Le statut de l'utilisateur
		 * @return int : le nombre d'utilisateurs ajoutés
		 */
		public function createUser($email, $password, $admin)
		{
			$query = '
				INSERT INTO users(email, password, admin)
				VALUES (:email, :password, :admin)
			';

			$params = array(
				'email' => $email,
				'password' => $password,
				'admin' => $admin,
			);

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}

		/**
		 * Supprime tous les users dont l'id fait partie du tableau fourni
		 * @param $users_ids : Tableau des id des users à supprimer
		 * @return int : Nombre de lignes supprimées
		 */
		public function deleteUsersIn($users_ids)
		{
			$query = "
				DELETE FROM users
				WHERE id ";
		
			//On génère la clause IN et les paramètres adaptés depuis le tableau des id	
			$generted_in = $this->generateInFromArray($users_ids);
			$query .= $generted_in['QUERY'];
			$params = $generted_in['PARAMS'];

			return $this->runQuery($query, $params, model::ROWCOUNT);
		}
		
		
		
		
		/**
		 * création de la table relai
		 * @return int : Le nombre de lignes modifiées
		 */
		public function createtablerelai()
		{
			/*
      $query = '
				CREATE TABLE IF NOT EXISTS relai (
				numero tinyint(1) unsigned NOT NULL,
				carte_id tinyint(1) unsigned NOT NULL,
				etat char(1) NOT NULL,
				time int(11) unsigned NOT NULL,
				PRIMARY KEY (numero,carte_id,time),
				KEY etat (etat)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1
			';
      */
			
			$filenamesql = $GLOBALS["model_path"] . 'sql' . DIRECTORY_SEPARATOR . 'CreateTableRelai.sql';
			try {
			$query = implode('', file($filenamesql));
      $tab = array( CHR(13) => " ", CHR(10) => " ", CHR(9) => " " );
      $query = strtr($query,$tab); 
			}
			catch (Exception $e) {
				return array(
					"valid" => false,
					"query" => $query,
					"pdoerror" => $e->getCode(),
					"pdomessage" => $e->getMessage()
				);

			}
			$params = array();
			return $this->runQuery($query, $params, model::NO);
		}		

		public function createtablean()
		{
			/*
      $query = '
				CREATE TABLE IF NOT EXISTS `an` (
				`numero` tinyint(1) unsigned NOT NULL,
				`carte_id` tinyint(1) unsigned NOT NULL,
				`etat` decimal(5,2) NOT NULL,
				`time` int(11) unsigned NOT NULL,
				PRIMARY KEY (`numero`,`carte_id`,`time`),
				KEY `etat` (`etat`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1
			';
      */
     		$filenamesql = $GLOBALS["model_path"] . 'sql' . DIRECTORY_SEPARATOR . 'CreateTableAn.sql';
			try {
				if (!file_exists($filenamesql)) {
					throw new Exception('Fichier inexistant');
				}
				$query = implode('', file($filenamesql));
        $tab = array( CHR(13) => " ", CHR(10) => " ", CHR(9) => " " );
        $query = strtr($query,$tab); 
				$params = array();
				return $this->runQuery($query, $params, model::NO);
			}
			catch (Exception $e) {
				return array(
					"valid" => false,
					"query" => 'CreateTableAn.sql',
					"pdoerror" => 'CreateTableAn.sql introuvable',
					"pdomessage" => $e->getMessage()
				);

			}
		}		

		public function createtablebtn() {
			/*
      $query = '
				CREATE TABLE IF NOT EXISTS `btn` (
				`numero` tinyint(1) unsigned NOT NULL,
				`carte_id` tinyint(1) unsigned NOT NULL,
				`etat` char(2) NOT NULL,
				`time` int(11) unsigned NOT NULL,
				PRIMARY KEY (`numero`,`carte_id`,`time`),
				KEY `etat` (`etat`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1
			';
      */
    		$filenamesql = $GLOBALS["model_path"] . 'sql' . DIRECTORY_SEPARATOR . 'CreateTableBtn.sql';
			try {
			$query = implode('', file($filenamesql));
      $tab = array( CHR(13) => " ", CHR(10) => " ", CHR(9) => " " );
      $query = strtr($query,$tab); 
			}
			catch (Exception $e) {
				return array(
					"valid" => false,
					"query" => $query,
					"pdoerror" => $e->getCode(),
					"pdomessage" => $e->getMessage()
				);

			}
			$params = array();
			return $this->runQuery($query, $params, model::NO);
		}		
		

		public function createtablecnt() {
			/*
      $query = '
				CREATE TABLE IF NOT EXISTS `cnt` (
				`numero` tinyint(1) unsigned NOT NULL,
				`carte_id` tinyint(1) unsigned NOT NULL,
				`etat` int(1) unsigned NOT NULL,
				`time` int(11) unsigned NOT NULL,
				PRIMARY KEY (`numero`,`carte_id`,`time`),
				KEY `etat` (`etat`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1
			';
      */
    		$filenamesql = $GLOBALS["model_path"] . 'sql' . DIRECTORY_SEPARATOR . 'CreateTableCnt.sql';
			try {
			$query = implode('', file($filenamesql));
      $tab = array( CHR(13) => " ", CHR(10) => " ", CHR(9) => " " );
      $query = strtr($query,$tab); 
			}
			catch (Exception $e) {
				return array(
					"valid" => false,
					"query" => $query,
					"pdoerror" => $e->getCode(),
					"pdomessage" => $e->getMessage()
				);

			}
			$params = array();
			return $this->runQuery($query, $params, model::NO);
		}

		public function createtablevariable() {
			/*
      $query = '
				CREATE TABLE IF NOT EXISTS `variable` (
				`numero` tinyint(1) unsigned NOT NULL,
				`etat` int(1) unsigned NOT NULL,
				`time` int(11) unsigned NOT NULL,
				PRIMARY KEY (`numero`,`time`),
				KEY `etat` (`etat`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1		
			';
      */
    		$filenamesql = $GLOBALS["model_path"] . 'sql' . DIRECTORY_SEPARATOR . 'CreateTableVariable.sql';
			try {
			$query = implode('', file($filenamesql));
      $tab = array( CHR(13) => " ", CHR(10) => " ", CHR(9) => " " );
      $query = strtr($query,$tab); 
			}
			catch (Exception $e) {
				return array(
					"valid" => false,
					"query" => $query,
					"pdoerror" => $e->getCode(),
					"pdomessage" => $e->getMessage()
				);

			}
			$params = array();
			return $this->runQuery($query, $params, model::NO);
		}

		public function createtablevartxt() {
			/*
      $query = '
				CREATE TABLE IF NOT EXISTS `vartxt` (
				`numero` tinyint(1) unsigned NOT NULL,
				`etat` varchar(16) NOT NULL,
				`time` int(11) unsigned NOT NULL,
				PRIMARY KEY (`numero`,`time`),
				KEY `etat` (`etat`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1			
			';
      */
    		$filenamesql = $GLOBALS["model_path"] . 'sql' . DIRECTORY_SEPARATOR . 'CreateTableVartxt.sql';
			try {
			$query = implode('', file($filenamesql));
      $tab = array( CHR(13) => " ", CHR(10) => " ", CHR(9) => " " );
      $query = strtr($query,$tab); 
			}
			catch (Exception $e) {
				return array(
					"valid" => false,
					"query" => $query,
					"pdoerror" => $e->getCode(),
					"pdomessage" => $e->getMessage()
				);

			}
			$params = array();
			return $this->runQuery($query, $params, model::NO);
		}

		public function createtabletrace() {
			/*
      $query = '
				CREATE TABLE IF NOT EXISTS `trace` (
				`id` int(1) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` tinyint(1) unsigned NOT NULL,
				`type` varchar(10) NOT NULL,
				`from` int(1) NOT NULL,
				`texte` varchar(255) NOT NULL,
				`time` int(1) unsigned NOT NULL,
				`timezone` varchar(255),
				PRIMARY KEY (`id`),
				KEY `user_id` (`user_id`),
				KEY `type` (`type`),
				KEY `from` (`from`),
				KEY `time` (`time`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1			
			';
      */
    		$filenamesql = $GLOBALS["model_path"] . 'sql' . DIRECTORY_SEPARATOR . 'CreateTableTrace.sql';
			try {
			$query = implode('', file($filenamesql));
      $tab = array( CHR(13) => " ", CHR(10) => " ", CHR(9) => " " );
      $query = strtr($query,$tab); 
			}
			catch (Exception $e) {
				return array(
					"valid" => false,
					"query" => $query,
					"pdoerror" => $e->getCode(),
					"pdomessage" => $e->getMessage()
				);

			}
			$params = array();
			return $this->runQuery($query, $params, model::NO);
		}

		public function droptable($table) {
			$query = '
				DROP TABLE IF EXISTS `' . $table .'`
			';

			$params = array();

			return $this->runQuery($query, $params, model::NO);
		}
		
		
		public function altertabletracedropanneemoisjour () {
			if ($this->fieldExist("annee", "trace")) {
				$query = 'ALTER TABLE `trace` DROP `annee`, DROP `mois`, DROP `jour`';
				$params = array();
				return $this->runQuery($query, $params, model::NO);
			}
			else {
					return array(
						"valid" => true,
						"query" => $query,
						"pdoerror" => 0,
						"pdomessage" => ""
					);
			}
			
		}

		
		public function altertabletraceaddtimezone () {
			if (!$this->fieldExist("timezone", "trace")) {
				$query = '
					ALTER TABLE `trace` ADD `timezone` varchar(255)
				';

				$params = array();
				return $this->runQuery($query, $params, model::NO);
			}
			else {
					return array(
						"valid" => true,
						"query" => $query,
						"pdoerror" => 0,
						"pdomessage" => ""
					);
			}
		}

		public function altertabletraceaddmicrotime () {
			if (!$this->fieldExist("microtime", "trace")) 
			{
				$query = 'ALTER TABLE `trace` ADD `microtime` SMALLINT UNSIGNED NULL DEFAULT NULL';
				$params = array();
				return $this->runQuery($query, $params, model::NO);
			}
			else {
					return array(
						"valid" => true,
						"query" => $query,
						"pdoerror" => 0,
						"pdomessage" => ""
					);
			}
		}

		
		public function addindex($table, $field) {
			$query = 'ALTER TABLE `'.$table.'` ADD INDEX `'.$field.'` ( `'.$field.'`)';
			$params = array();
			return $this->runQuery($query, $params, model::NO);
		}

		public function addindex_numero_time() {
			foreach( array('an', 'btn', 'relai', 'cnt', 'variable') as $table) {
				//Creation des indexes sur numero et time 
				foreach( array('numero', 'time') as $field) {
					//$result = mysql_query("SHOW INDEXES FROM `".$table."` WHERE Key_name = '".$field."';");
					if ($this->ExistIndexesForTable($table, $field)) {
						$this->addindex($table,$field); 
					}
				}
				
				if ($this->fieldExist("carte_id", $table)) {
					$params = array();
					$query = 'DELETE FROM `'.$table.'` WHERE `carte_id` != 0';
					$this->runQuery($query, $params, model::NO);

					$query = 'ALTER TABLE `'.$table.'` DROP PRIMARY KEY';
					$this->runQuery($query, $params, model::NO);
					
					$query = 'ALTER TABLE `'.$table.'` ADD PRIMARY KEY (`numero`,`time`)';
					$this->runQuery($query, $params, model::NO);

					$query = 'ALTER TABLE `'.$table.'` DROP `carte_id`';
					$this->runQuery($query, $params, model::NO);
				}
			}
		}

		public function addindex_trace() {
			$table='trace';
			//Creation des indexes sur microtime, alltime, type_time
			foreach( array('microtime', 'alltime', 'type_time') as $field) {
				if (!$this->ExistIndexesForTable($table, $field))
					$this->addindex($table,$field); 
			}
		}

		public function forceengine() {
			$rows = $this->tablestatus;			
			foreach( $rows as $row) {
				if ( $row["Engine"] == "InnoDB" ) {
					$query = 'ALTER TABLE `".$row["Name"]."` ENGINE = MYISAM';
					$params = array();
					$this->runQuery($query, $params, model::NO);
				}
			}
		}
		
		public function purgeetatvide ($table) {
			$query = "DELETE FROM `".$table."` WHERE `etat` = '' ";
			$params = array();
			return $this->runQuery($query, $params, model::ROWCOUNT);
		}
		
		
		public function purgedouble ($table) {
			$query = "SELECT * FROM `".$table."` ORDER BY `numero`, `time` ASC ";
			$params = array();
			$rows = $this->runQuery($query, $params, model::FETCHALL);
			$numero = "";
			foreach ($rows as $row) {
				if ( $numero != $row["numero"] )
					unset($prev_etat);
				if ( isset($prev_etat) && $prev_etat == $row["etat"] ) {
					$query = "DELETE FROM `".$table."` WHERE ";
					$query .= "`numero` = ".$row["numero"]." AND `time` = ".$row["time"];
					$rowcount = $this->runQuery($query, $params, model::ROWCOUNT);  
				}
				$numero = $row["numero"];
				$prev_etat = $row["etat"];
			}
		}
		
		public function optimizetable($table) {
			$query = "OPTIMIZE TABLE `" . $table . "`";
			$datas = array();
			$this->runQuery($query, $datas, model::NO);
			return true;
		}

		public function fielddecimalanetat () {
			$query = "SELECT `etat` FROM `an`";
			$params = array();
			$meta = $this->meta($query, $param, 0);
			if (($meta['precision'] == 0) || ($meta['len'] == 7)) {
				$query = "ALTER TABLE `an` CHANGE `etat` `etat` DECIMAL( 6, 3 ) NOT NULL";
				return $this->runQuery($query, $datas, model::ROWCOUNTARRAY);
			}
			else {
					return array(
						"valid" => true,
						"query" => null,
						"pdoerror" => 0,
						"pdomessage" => "",
						"rowcount" => 0
					);
			}
		}	
		
		public function fielddecimalcntetat () {
			$query = "SELECT `etat` FROM `cnt`";
			$params = array();
			$meta = $this->meta($query, $param, 0);
			if (($meta['precision'] == 0) || ($meta['len'] <> 12)) {
				$query = "ALTER TABLE `cnt` CHANGE `etat` `etat` DECIMAL( 15, 3 ) NOT NULL";
				$this->runQuery($query, $datas, model::ROWCOUNTARRAY);
			}
			else {
					return array(
						"valid" => true,
						"query" => null,
						"pdoerror" => 0,
						"pdomessage" => "",
						"rowcount" => 0
					);
			}
		}	

		public function fielddecimalvariableetat () {
			$query = "SELECT `etat` FROM `variable`";
			$params = array();
			$meta = $this->meta($query, $param, 0);
			if ($meta['precision'] == 0) {
				$query = "ALTER TABLE `variable` CHANGE `etat` `etat` DECIMAL( 10, 3 ) NOT NULL";
				$this->runQuery($query, $datas, model::ROWCOUNTARRAY);
			}
			else {
					return array(
						"valid" => true,
						"query" => null,
						"pdoerror" => 0,
						"pdomessage" => "",
						"rowcount" => 0
					);
			}
		}	
		
		public function fielddecimalbtnetat () {
			$query = "SELECT `etat` FROM `btn`";
			$params = array();
			$meta = $this->meta($query, $param, 0);
			if ($meta['len'] != 1) {
				$query = "ALTER TABLE `btn` CHANGE `etat` `etat` CHAR( 1 ) NOT NULL";
				$retour = $this->runQuery($query, $datas, model::ROWCOUNTARRAY);
				$query = "UPDATE `btn` SET `etat` = '1' WHERE `etat` = 'u'";
				$this->runQuery($query, $datas, model::ROWCOUNT);
				$query = "UPDATE `btn` SET `etat` = '0' WHERE `etat` = 'd'";
				$this->runQuery($query, $datas, model::ROWCOUNT);
				$query = "DELETE FROM `btn` WHERE `etat` <> '0' AND `etat` <> '1' ";
				$this->runQuery($query, $datas, model::ROWCOUNT);
				return $retour;
			}
			else {
				return array(
					"valid" => true,
					"query" => null,
					"pdoerror" => 0,
					"pdomessage" => "",
					"rowcount" => 0
				);
			}

		}	

		public function fielddecimaltraceetat () {
			$query = "SELECT `etat` FROM `trace`";
			$params = array();
			$meta = $this->meta($query, $param, 0);
			if ($meta['len'] != 255) {
				$query = "ALTER TABLE `trace` CHANGE `texte` `texte` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL";
				return $this->runQuery($query, $datas, model::ROWCOUNTARRAY);
			}
			else {
					return array(
						"valid" => true,
						"query" => null,
						"pdoerror" => 0,
						"pdomessage" => "",
						"rowcount" => 0
					);
			}

		}	

		/**
		 * Insert une nouvelle trace
		 * @param string $date : La date d'envoie du message
		 * @param string $target : Numéro auquel a été envoyé le message
		 * @param string $content : Texte du message
		 * @return int : le nombre de SMS créés
		 */
		public function createTrace($userid, $type, $ip, $message, $strtime, $timezone, $microtime) {
			$query = '
				INSERT INTO `trace` (`id`, `user_id`, `type`, `ipfrom`, `texte`, `timeutc`, `timezone`, `microtime`)
				VALUES (NULL, :userid, :type, :ip, :message, :strtime, :timezone, :microtime)
			';

			$params = array(
				'userid' => $userid,
				'type' => $type,
				'ip' => $ip,
				'message' => $message,
				'strtime' => $strtime,
				'timezone' => $timezone,
				'microtime' => $microtime
			);

			return $this->runQuery($query, $params, model::ROWCOUNT); //On retourne le nombre de lignes ajoutés
		}
		
		public function getTraceTypes() {
			$query = "SELECT distinct(`type`) FROM `trace` ORDER BY `type`";
			$params = array();
			return $this->runQuery($query, $params, model::FETCHALL);
		}
		
		public function getLastAlerts($nbrows=6) {
			$query = "SELECT `id`, `carte_id`, `class`, `no`, `message`, CONVERT_TZ(`timeutc`, 'GMT',`timezone`) as `localtime`, `timezone`, `microtime`, `trace_id` FROM `alert` ORDER BY `id` DESC limit ".$nbrows;
			$params = array();
			return $this->runQuery($query, $params, model::FETCHALL);
		}

		/**
		 * Insert une nouvelle alert
		 * @param string $date : La date d'envoie du message
		 * @param string $target : Numéro auquel a été envoyé le message
		 * @param string $content : Texte du message
		 * @return int : le nombre d'alert créee
		 */
		public function createAlert($carteid, $class, $no, $message, $timeutc, $timezone, $microtime, $traceid=null) {
			$query = '
				INSERT INTO `alert` (`id`, `carte_id`, `class`, `no`, `message`, `timeutc`, `timezone`, `microtime`, `trace_id`)
				VALUES (NULL, :carteid, :class, :no, :message, :timeutc, :timezone, :microtime, :traceid)
			';

			$params = array(
				'carteid' 	=> $carteid,
				'class' 	=> $class,
				'no' 		=> $no,
				'message' 	=> $message,
				'timeutc' 	=> $timeutc,
				'timezone' 	=> $timezone,
				'microtime' => $microtime,
				'traceid' 	=> $traceid
			);

			return $this->runQuery($query, $params, model::ROWCOUNT); //On retourne le nombre de lignes ajoutés
		}
		
		/**
		 * Récupère la liste des tables
		 * @return array : Retourne un tableau avec les commands adaptés
		 */
		public function showtables()
		{
			$query = "SHOW TABLES";
			$params = array();
			return $this->runQuery($query, $params, model::FETCHALL);
		}
	}

