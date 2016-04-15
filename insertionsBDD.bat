SET token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL215YXBpLmNvbVwvYXV0aGVudGljYXRlIiwiaWF0IjoxNDYwNjQzOTQ0LCJleHAiOjE0NjA4NTk5NDQsIm5iZiI6MTQ2MDY0Mzk0NCwianRpIjoiZTY3M2IzZjgwNGI0ZThmMDQxNjE5NTNiMDEyZWZkZDgifQ.73vFiRmDZ81C9h9YCJJE_a39qz2eJzbO5_PWl6O5gtI

REM Forfait
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "nom=Forfait%202&resum=Resume forfait&prix=30&duree_jours=10" "http://myapi.com/forfait?api_key="%token%
REM Abonnement
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "id_forfait=1&debut=2015-01-01" "http://myapi.com/abonnement?api_key="%token%
REM Distributeur
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "nom=New Distributeur&adresse=21 rue erard&cpostal=75008&ville=Paris&pays=France&telephone=01020405" "http://myapi.com/distributeur?api_key="%token%
REM Employe
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "id_personne=1&id_fonction=1" "http://myapi.com/employe?api_key="%token%
REM Genre
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "nom=New wave" "http://myapi.com/genre?api_key="%token%
REM Film
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "id_genre=17&id_distributeur=70&titre=Hostel%3A%20Part%20III&resum=Three%20young%20women%20are%20lured%20into%20a%20Slovakian%20hostel.%20%20Once%20there%2C%20they%20are%20subjected%20to%20all%20kinds%20of%20torture%20and%20hell...%20%20Can%20they%20escape%3F&date_debut_affiche=2007-06-29&date_fin_affiche=2007-08-29&duree_minutes=120&annee_production=2006" "http://myapi.com/film?api_key="%token%
REM Fonction
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "nom=associe&salaire=30000&cadre=1" "http://myapi.com/fonction?api_key="%token%
REM HistoriqueMembre
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "id_membre=1&id_seance=1&date=2016-04-16 10:00:00" "http://myapi.com/historique_membre?api_key="%token%
REM Membre
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "id_personne=1&id_abonnement=1&date_inscription=2015-08-17&debut_abonnement=2015-09-01" "http://myapi.com/membre?api_key="%token%
REM Personne
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "nom=Le Bricoleur&prenom=Bob&date_naissance=1997-01-05&email=bricoleur.bob@lycos.fr&adresse=1 rue Ketanou&cpostal=75000&ville=Paris&pays=France" "http://myapi.com/personne?api_key="%token%
REM Reduction
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "nom=Reduction%201&date_debut=2016-01-01&date_fin=2016-02-01&pourcentage_reduction=20" "http://myapi.com/reduction?api_key="%token%
REM Salle
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "numero_salle=30&nom_salle=Guiks&etage_salle=3&places=250" "http://myapi.com/salle?api_key="%token%
REM Seance
curl -X POST --header "Content-Type: application/x-www-form-urlencoded" --header "Accept: application/json" -d "id_film=1&id_salle=1&id_personne_ouvreur=1&id_personne_technicien=1&id_personne_menage=1&debut_seance=2016-04-04 10:25:00&fin_seance=2016-04-04 12:05:00" "http://myapi.com/seance?api_key="%token%