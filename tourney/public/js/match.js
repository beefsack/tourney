function getMatchForm(matchid)
{

	dijit.byId('scoredialog').attr('href', '/tourney/public/ajax/matchscoreform/matchid/' + matchid);
	dijit.byId('scoredialog').show();

}

function saveMatchForm()
{
	// Only try to save if the form is valid
	if (dijit.byId("scoreinputform").isValid()) {
		// Sends data to a URL using POST, there is also a form xhrGet to send using GET
		dojo.xhrPost({
			// The url to submit the form data to
			url: "/tourney/public/ajax/savematchscore",
			// The form to submit
			form: dojo.byId("scoreinputform"),
			// It is just a text form (ie. no file uploads)
			handleAs: "text",
			// What to do once the page has been loaded (ie. the form was successfully sent)
			load: function(data) {
				// Reload the tree and hide the dialog
				dijit.byId('treecontentpane').attr('href', '/tourney/public/ajax/tree/tourneyid/' + document.getElementById('tourneyid').value);
				dijit.byId('scoredialog').hide();
			},
			// What to do if there is an error
			error: function(error) {
				alert('Error saving match');
			}
		});
	}
	return false; // This stops the form from actually submitting and opening a new page.  We want to stay on the same page and let ajax reload element
}