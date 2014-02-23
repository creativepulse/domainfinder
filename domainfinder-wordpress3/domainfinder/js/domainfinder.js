
/**
 * Domain Finder
 *
 * @version 1.2
 * @author Creative Pulse
 * @copyright Creative Pulse 2011-2014
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


function cpwdg_domainfinder_h_response() {
	if (document.cpwdg_domainfinder_ajax.readyState == 4) {
		if (document.cpwdg_domainfinder_ajax.status == 200) {
			var data = {info: document.cpwdg_domainfinder_ajax.responseText};
			while (true) {
				var i = data.info.indexOf("\n");
				if (i == -1) {
					break;
				}
				
				var st = data.info.substr(0, i).replace(/^\s+|\s+$/g, "");
				data.info = data.info.substr(i + 1);

				if (st == "") {
					break;
				}
				
				i = st.indexOf(":");
				if (i > -1) {
					data[st.substr(0, i).replace(/^\s+|\s+$/g, "").toLowerCase()] = st.substr(i + 1).replace(/^\s+|\s+$/g, "");
				}
			}

			for (var k in document.cpwdg_domainfinder_inst) {
				if (document.cpwdg_domainfinder_inst.hasOwnProperty(k)) {
					document.cpwdg_domainfinder_inst[k].h_response(data);
				}
			}

			document.cpwdg_domainfinder_ajax = null;

			for (var k in document.cpwdg_domainfinder_inst) {
				if (document.cpwdg_domainfinder_inst.hasOwnProperty(k)) {
					document.cpwdg_domainfinder_inst[k].process_queue();
				}
			}
		}
		else {
			alert("HTTP error status " + document.cpwdg_domainfinder_ajax.status + ":\n" + document.cpwdg_domainfinder_ajax.responseText);
			document.cpwdg_domainfinder_ajax = null;
		}
	}
}

function CpWdgJs_DomainFinder(iname) {
	this.iname = iname;

	this.conf = document.cpwdg_domainfinder_conf[this.iname];

	this.txt_wdg = document.getElementById(iname + "_text");
	
	var btn = document.getElementById(iname + "_button");
	btn.setAttribute("iname", this.iname);
	btn.onclick = function () { document.cpwdg_domainfinder_inst[this.getAttribute("iname")].search(); }

	this.input_panel = document.getElementById(this.iname + "_input_panel");
	
	this.result_panel = document.getElementById(this.iname + "_result_panel");
	document.getElementsByTagName("body")[0].appendChild(this.result_panel);

	this.tbody = document.getElementById(this.iname + "_result_items");
	while (this.tbody.firstChild) { //-*
		this.tbody.removeChild(this.tbody.firstChild);
	}
	
	btn = document.getElementById(this.iname + "_close_button");
	if (btn) {
		btn.setAttribute("iname", this.iname);
		btn.onclick = function () { document.cpwdg_domainfinder_inst[this.getAttribute("iname")].close(); }
	}

	this.data = {};
	this.data_count = 0;

	this.last_id = -1;
	document.cpwdg_domainfinder_ajax = null;

	this.h_response = function (data) {
		if (data.domain && data.key && data.task) {
			var item_name = "";
			for (var k in this.data) {
				if (this.data.hasOwnProperty(k) && this.data[k].domain == data.domain && this.data[k].key == data.key) {
					item_name = k;
				}
			}
			
			if (item_name != "") {
				if (data.task == "search" && this.data[item_name].state == 1) {
					if (data.error != "") {
						alert("Error while searching domain " + data.domain + ":\n" + data.error);
					}
					
					this.data[item_name].host = data.host;
					
					var icon_wdg = document.getElementById(item_name + "_icon");
					var text_wdg = document.getElementById(item_name + "_text");
					while (text_wdg.firstChild) {
						text_wdg.removeChild(text_wdg.firstChild);
					}
					
					if (data.availability == "available") {
						this.data[item_name].state = 3;
						icon_wdg.className = "cpwdg_domainfinder_icon_available";
						text_wdg.appendChild(document.createTextNode(this.conf.lang_str.domain_available));
					}
					else if (data.availability == "unavailable") {
						this.data[item_name].state = 2;
						icon_wdg.className = "cpwdg_domainfinder_icon_unavailable";
						text_wdg.appendChild(document.createTextNode(this.conf.lang_str.domain_unavailable));
					}
					else {
						this.data[item_name].state = 4;
						icon_wdg.className = "cpwdg_domainfinder_icon_unavailable";
						text_wdg.appendChild(document.createTextNode(this.conf.lang_str.domain_error));
					}               
				}
			}
		}
	}

	this.process_queue = function () {
		if (document.cpwdg_domainfinder_ajax != null) {
			// another search is active
			return;
		}

		var item_name = "";
		for (var k in this.data) {
			if (this.data.hasOwnProperty(k) && this.data[k].state == 0) {
				item_name = k;
			}
		}
		if (item_name == "") {
			// no search in the queue
			return;
		}

		if (window.XMLHttpRequest) {
			document.cpwdg_domainfinder_ajax = new window.XMLHttpRequest();
		}
		else if (window.ActiveXObject) {
			document.cpwdg_domainfinder_ajax = new window.ActiveXObject("Microsoft.XMLHTTP");
		}

		if (!document.cpwdg_domainfinder_ajax) {
			alert("Critical Error: Your browser was unable to initialize the AJAX sub-system");
		}
		else {
			this.data[item_name].state = 1;
			document.cpwdg_domainfinder_ajax.open("GET", this.conf.call_url + "/search.php?domain=" + encodeURIComponent(this.data[item_name].domain) + "&key=" + this.data[item_name].key + "&task=search&mhs=" + document.cpwdg_domainfinder_mhs + "&nhc=" + document.cpwdg_domainfinder_nhc, true);
			document.cpwdg_domainfinder_ajax.onreadystatechange = cpwdg_domainfinder_h_response;
			document.cpwdg_domainfinder_ajax.send();
		}
	}

	this.search_domain = function (domain) {

		// check if domain is already in the list
		var foundit = false;
		for (var k in this.data) {
			if (this.data.hasOwnProperty(k) && this.data[k].domain == domain) {
				foundit = true;
				break;
			}
		}
		if (foundit) {
			return;
		}


		// prepare names
		this.last_id++;
		var item_name = this.iname + "_item_" + this.last_id;


		// create listing
		var tr = document.createElement("tr");
		tr.setAttribute("id", item_name + "_tr");

		if (this.tbody.firstChild) {
			this.tbody.insertBefore(tr, this.tbody.firstChild);
		}
		else {
			this.tbody.appendChild(tr);
		}

		if (this.conf.cb_create_listing != "" && typeof window[this.conf.cb_create_listing] == "function") {
			window[this.conf.cb_create_listing](tr, this.iname, item_name, this.conf.lang_str, domain);
		}
		else {
			var td = document.createElement('td');
			tr.appendChild(td);
		
				var div = document.createElement('div');
				td.appendChild(div);
				div.setAttribute("id", item_name + "_icon");
				div.className = "cpwdg_domainfinder_icon_searching";


			var td = document.createElement('td');
			tr.appendChild(td);
		
				var div = document.createElement('div');
				td.appendChild(div);
				div.setAttribute("id", item_name + "_text");
				div.className = "cpwdg_domainfinder_status_searching";
				div.appendChild(document.createTextNode(this.conf.lang_str.domain_searching));


			var td = document.createElement('td');
			tr.appendChild(td);
		
				var div = document.createElement('div');
				td.appendChild(div);
				div.setAttribute("id", item_name + "_domain");
				div.className = "cpwdg_domainfinder_domain";
				div.appendChild(document.createTextNode(domain));


			var td = document.createElement('td');
			tr.appendChild(td);
		
				var div = document.createElement('div');
				td.appendChild(div);
				div.className = "cpwdg_domainfinder_icon_remove";
				div.setAttribute("iname", this.iname);
				div.setAttribute("item_name", item_name);
				div.onclick = function () { document.cpwdg_domainfinder_inst[this.getAttribute("iname")].remove(this.getAttribute("item_name")) };
				div.appendChild(document.createTextNode("x"));


			var td = document.createElement('td');
			tr.appendChild(td);
		
				var div = document.createElement('div');
				td.appendChild(div);
				div.className = "cpwdg_domainfinder_icon_moreinfo";
				div.setAttribute("iname", this.iname);
				div.setAttribute("item_name", item_name);
				div.onclick = function () { document.cpwdg_domainfinder_inst[this.getAttribute("iname")].info(this.getAttribute("item_name")) };
				div.appendChild(document.createTextNode(this.conf.lang_str.more_info));
		}


		// register data
		this.data[item_name] = {
			domain: domain,
			state: 0, // 0 = not called, 1 = waiting for search, 2 = status not available, 3 = status available, 4 = error
			key: Math.round(Math.random() * 2147483647),
			host: ""
		};
		this.data_count++;

		if (this.data_count == 1) {
			this.open();
		}

		this.process_queue();
		
		return tr;
	}

	this.search = function () {
		var domain = this.txt_wdg.value;
		if (domain == "") {
			return;
		}
		
		var tlds = [];
		for (var i = 0, len = this.conf.tlds.length; i < len; i++) {
			if (document.getElementById(this.iname + "_tld_" + this.conf.tlds[i]).checked) {
				tlds.push(this.conf.tlds[i]);
			}
		}

		var tld = "";
		var i = domain.indexOf(".");
		if (i > -1) {
			tld = domain.substr(i);
			domain = domain.substr(0, i);

			var k = tld.indexOf(".", 1);
			if (domain.toLowerCase() == "www" && k > -1) {
				domain = tld.substr(1, k - 1);
				tld = tld.substr(k);
			}
			
			if (!tld.match(/^(\.[a-z]+)+$/i)) {
				alert(this.conf.lang_str.invalid_tld);
				return;
			}
		}

		if (!domain.match(/^[a-z0-9-]+(\.[a-z0-9-]+)*$/i)) {
			alert(this.conf.lang_str.invalid_domain);
			return;
		}

		if (domain.length < 3) {
			alert(this.conf.lang_str.domain_too_short);
			return;
		}

		if (domain.length > 64) {
			alert(this.conf.lang_str.domain_too_long);
			return;
		}
		
		for (var i = tlds.length - 1; i >= 0; i--) {
			if (tld == "" || domain + tld != domain + "." + tlds[i]) {
				this.search_domain(domain + "." + tlds[i]);
			}
		}
		if (tld != "") {
			this.search_domain(domain + tld);
		}
	}

	this.remove = function (item_name) {
		var tr = document.getElementById(item_name + "_tr");
		if (tr) {
			tr.parentNode.removeChild(tr);
		}
		
		if (typeof this.data[item_name] != "undefined") {
			delete this.data[item_name];
			this.data_count--;
		}

		if (this.data_count == 0) {
			this.close();
		}
	}

	this.info = function (item_name) {
		if (typeof this.data[item_name] == "undefined" || this.data[item_name].state < 2 || this.data[item_name].state > 3) {
			alert(this.conf.lang_str.information_na);
			return;
		}
		window.open(this.conf.call_url + "/info.php?url=" + encodeURIComponent(this.conf.call_url) + "&domain=" + encodeURIComponent(this.data[item_name].domain) + "&host=" + encodeURIComponent(this.data[item_name].host), '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
	}

	this.open = function () {
		if (this.conf.cb_open != "" && typeof window[this.conf.cb_open] == "function") {
			var info = window[this.conf.cb_open](this.iname);
		}
		else {
			var info = {
				x: this.conf.panel_offset_x,
				y: this.conf.panel_offset_y + this.input_panel.offsetHeight,
				min_w: 0
			};

			var obj = this.input_panel;
			while (obj) {
				info.x += obj.offsetLeft;
				info.y += obj.offsetTop;
				obj = obj.offsetParent;
			}
		}

		this.result_panel.style.left = info.x + "px";
		this.result_panel.style.top = info.y + "px";
		this.result_panel.style.display = "block";
		if (this.result_panel.offsetWidth < info.min_w) {
			this.result_panel.style.width = info.min_w + "px";
		}
	}

	this.close = function () {
		this.result_panel.style.display = "none";

		while (this.tbody.firstChild) {
			this.tbody.removeChild(this.tbody.firstChild);
		}
		
		this.data = {};
		this.data_count = 0;
	}

//    this.open(); //-*
}


function cpwdg_domainfinder_init() {
	document.cpwdg_domainfinder_inst = [];
	var i = 0;
	while (true) {
		i++;
		var iname = "cpwdg_domainfinder_" + i;
		var wdg = document.getElementById(iname);
		if (!wdg) {
			break;
		}

		document.cpwdg_domainfinder_inst[iname] = new CpWdgJs_DomainFinder(iname);
	}
}

if (window.addEventListener) {
	window.addEventListener("load", cpwdg_domainfinder_init, false);
}
else if (window.attachEvent) {
	window.attachEvent("onload", cpwdg_domainfinder_init);
}
