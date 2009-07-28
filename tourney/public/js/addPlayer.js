var playerCount = 0;

function addPlayer(player, container)
{
	if (player.isValid() && player.attr('value') != '' && player.attr('value') == player.attr('displayedValue')) {
		var playerField = document.createElement('input');
		playerField.setAttribute('type', 'hidden');
		playerField.setAttribute('name', 'player[]');
		playerField.setAttribute('value', player.attr('value'));
		
		var playerName = document.createTextNode(player.attr('value'));
		
		var playerNameDiv = document.createElement('div');
		playerNameDiv.setAttribute('style', 'float: left; clear: none; width: 150px;');
		playerNameDiv.appendChild(playerField);
		playerNameDiv.appendChild(playerName);
		
		var removeLink = document.createElement('input');
		removeLink.setAttribute('value', 'Remove');
		removeLink.setAttribute('type', 'button');
		removeLink.setAttribute('onClick', 'removePlayer(this);')
		
		var removeDiv = document.createElement('div');
		removeDiv.setAttribute('style', 'float: left; clear: none;');
		removeDiv.appendChild(removeLink);
		
		var playerDiv = document.createElement('div');
		playerDiv.setAttribute('style', 'float: left; clear: left;');
		playerDiv.appendChild(playerNameDiv);
		playerDiv.appendChild(removeDiv);
		
		container.appendChild(playerDiv);
		
		player.attr('value', '');
		
		playerCount++;
		
	} else {
		alert('Invalid player');
	}
}

function removePlayer(player)
{
	removeNode = player.parentNode.parentNode;
	removeNode.parentNode.removeChild(removeNode);
	playerCount--;
}

function checkPlayers()
{
	if (playerCount < 2) {
		alert('At least two players required for tournament to be created');
		return false;
	} else {
		return true;
	}
}