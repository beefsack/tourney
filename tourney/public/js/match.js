function getMatchForm(matchid)
{

	dijit.byId('scoredialog').attr('href', '/tourney/public/ajax/matchform/matchid/' + matchid);
	dijit.byId('scoredialog').show();

}

function saveMatchForm()
{
	// learned and borrowed from http://dojocampus.org/explorer/#Dojo_IO_XMLHttpRequest_Form
	dojo.xhrPost({
		url: "/tourney/public/ajax/savematch", // URL to call
		// Function to use when a successful response is recieved
		load: function(response, ioArgs){
			//Dojo recommends that you always return(response); to propagate 
			//the response to other callback handlers. Otherwise, the error 
			//callbacks may be called in the success case.
			dijit.byId('treecontentpane').attr('href', '/tourney/public/ajax/tree/tourneyid/' + document.getElementById('tourneyid').value);
			dijit.byId('scoredialog').hide();
			return response;
		},
		// Function to call if there is an error, such as 404
		error: function(response, ioArgs){
			alert('Error saving match');
			return response;
		},
		//Setting the 'form' parameter to the ID of a form on the page
		//submits that form to the specified URL
		form:"scoreinputform"
	});
	return false; // This stops the form from actually submitting and opening a new page
}