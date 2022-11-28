function deleteGroup(groupName) {
	if(confirm("You are about to delete this group '" + groupName + "'\r\n'OK' to delete, 'Cancel' to stop.")) {
		return true;
	}
	return false;
}

function setCheckStateAll(formId, masterCheckBoxId, checkedItem) {
	var form = document.getElementById(formId);
	if(form != null && checkedItem.type == "checkbox") {
		var masterCheckBox = document.getElementById(masterCheckBoxId);
		if(masterCheckBox != null && masterCheckBox.type == "checkbox") {
			if(masterCheckBox == checkedItem) {
				for (i = 0, n = form.elements.length; i < n; i++) {
					if(form.elements[i] != masterCheckBox && form.elements[i].type == "checkbox") {
						form.elements[i].checked = masterCheckBox.checked;
					}
				}
			}
			else {
				if(!checkedItem.checked) {
					masterCheckBox.checked = false;
				}
				else {
					var checkedCount = 0;
					var checkBoxesCount = 0;
					for (i = 0, n = form.elements.length; i < n && checkedCount == checkBoxesCount; i++) {
						var formElement = form.elements[i];
						if(formElement != masterCheckBox && formElement != checkedItem) {
							if(formElement.type == "checkbox") {
								checkBoxesCount++;
								checkedCount += (formElement.checked ? 1 : 0);
							}
						}
					}
					if(checkBoxesCount == checkedCount) {
						masterCheckBox.checked = true;
					}
				}
			}
		}
	}
}