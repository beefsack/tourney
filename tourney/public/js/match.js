function getMatchForm(matchid)
{

	dijit.byId('scoredialog').attr('href', '/tourney/public/ajax/matchform/matchid/' + matchid);
	dijit.byId('scoredialog').show();
	
}

function saveMatchForm()
{
	
}