function agentInfo(){
	data = {
	"name" : "Vicencia & Buckley A Division of HUB International Insurance Services",
	"address" : "6 Centerpointe Drive, #350",
	"address2" : "La Palma,CA 90623-2538",
	"phone1" : "(714) 739-3177",
	"phone2" : "(800) 223-9998",
	"fax" : "(714) 739-3188",
	"website" : "www.diveinsurance.com"
	};
	document.getElementById('nameVal').innerHTML= data.name; 
	document.getElementById('addressLineVal').innerHTML= HTMLEncode(data.address);
	document.getElementById('addressLine2Val').innerHTML= HTMLEncode(data.address2);
	document.getElementById('phone1Val').innerHTML= data.phone1;
	document.getElementById('phone2Val').innerHTML= data.phone2;
    document.getElementById('faxVal').innerHTML= data.fax; 
    document.getElementById('producerwebsite').innerHTML= data.website; 
}

function HTMLEncode(str) {
    var i = str.length,
        aRet = [];

    while (i--) {
        var iC = str[i].charCodeAt();
        if (iC < 65 || iC > 127 || (iC>90 && iC<97)) {
            aRet[i] = '&#'+iC+';';
        } else {
            aRet[i] = str[i];
        }
    }
    return aRet.join('');
}