<script>
	// jets: added constants for first conference and current conference to make future updates easier
	const CURRENT_CONFERENCE_YEAR = 2024;
	const FIRST_CONFERENCE_YEAR = 1974;
// when the buttons are created, they record what letter they are and the action when they are clicked
// So, this enables the buttons, when clicked, to report what letter they are
    function checkMe(e) {
		e.preventDefault();
	}
    function onButtonClick(buttonType, divID, divID2) {
        //e.preventDefault();
		// Determines which buttons have been clicked based on class
		// and sets the page to one as default view for loading
		// First, needs to change all of the classes correctly so that active button
		// has clicked class and the unclicked one does not
		if ((buttonType == "view") || (buttonType == "sortby") || (buttonType == "sortstyle")) {
			var clickedButton = document.getElementById(divID);
			var unclickedButton = document.getElementById(divID2);
			
			clickedButton.classList.add("filter-buttons-main-options-clicked");
			clickedButton.classList.remove("filter-buttons-main-options");
			
			unclickedButton.classList.add("filter-buttons-main-options");
			unclickedButton.classList.remove("filter-buttons-main-options-clicked");
		}
		else if (buttonType == "year") {
			var clickedButton = document.getElementById(divID);
			clickedButton.classList.add("filter-buttons-dropdown-options-clicked");
			clickedButton.classList.remove("filter-buttons-dropdown-options");
			
			var clickedYearText = document.getElementById("mainButton6");
			clickedYearText.innerHTML = clickedButton.innerHTML;
			for (let i=CURRENT_CONFERENCE_YEAR; i >= FIRST_CONFERENCE_YEAR; i--) {
			    var currButton = document.getElementById(i);
			    if (clickedButton != currButton) {
			        currButton.classList.add("filter-buttons-dropdown-options");
			        currButton.classList.remove("filter-buttons-dropdown-options-clicked");
			    }
		    }
			currButton = document.getElementById("1973");
			if (clickedButton != currButton) {
			    currButton.classList.add("filter-buttons-dropdown-options");
			    currButton.classList.remove("filter-buttons-dropdown-options-clicked");
			}
		}
		else if (buttonType == "category") {
			var clickedButton = document.getElementById(divID);
			clickedButton.classList.add("filter-buttons-dropdown-options-clicked");
			clickedButton.classList.remove("filter-buttons-dropdown-options");
			//alert(clickedButton);
			
			var clickedCatText = document.getElementById("mainButton7");
			clickedCatText.innerHTML = clickedButton.innerHTML;
			clickedCatText.classList.add("one-line-title");
			
		    var catContainerCount = document.getElementById("categoryButtonsDropdown");
		    var countCat = catContainerCount.getElementsByTagName("div").length;
			for (let i=0; i < countCat; i++) {
			    var currButton = document.getElementById("category" + i);
			    if (clickedButton != currButton) {
			        currButton.classList.add("filter-buttons-dropdown-options");
			        currButton.classList.remove("filter-buttons-dropdown-options-clicked");
			    }
		    }
		}
		for (let i=0; i < 2; i++) {
		    var currBtn = document.getElementById("mainButton" + i);
			if (currBtn.classList.contains("filter-buttons-main-options-clicked")) {
			    var view_button = currBtn;
			}
		}
		for (let i=2; i < 4; i++) {
		    var currBtn = document.getElementById("mainButton" + i);
			if (currBtn.classList.contains("filter-buttons-main-options-clicked")) {
			    var sort_by_button = currBtn;
			}
		}
		for (let i=4; i < 6; i++) {
		    var currBtn = document.getElementById("mainButton" + i);
			if (currBtn.classList.contains("filter-buttons-main-options-clicked")) {
			    var sort_style_button = currBtn;
			}
		}
		// "1973" is id for "View All Years" button
		for (let i=CURRENT_CONFERENCE_YEAR; i >= 1973; i--) {
		    var currBtn = document.getElementById(i);
			if (currBtn.classList.contains("filter-buttons-dropdown-options-clicked")) {
			    var year_button = currBtn;
			}
		}
		// Check how many categories there are
		// id "category0" is for "View All Categories" button
		var catContainerDiv = document.getElementById("categoryButtonsDropdown");
		var countCats = catContainerDiv.getElementsByTagName("div").length;
		for (let i=0; i < countCats; i++) {
		    var currBtn = document.getElementById("category" + i);
			if (currBtn) {
				if (currBtn.classList.contains("filter-buttons-dropdown-options-clicked")) {
			        var category_button = currBtn;
					//alert(category_button);
			    }
			}
		}
		// Show/hide table headers
		var tableViewSelected = document.getElementById("mainButton1");
		var coursesTableHeader = document.getElementById("tableCourses");
		var artPapersTableHeader = document.getElementById("tableArtPapers");
		var learningTableHeader = document.getElementById("tableLearning");
		var experienceTableHeader = document.getElementById("tableExperience");
		var publicationTableHeader = document.getElementById("tablePublication");
		var cafTableHeader = document.getElementById("tableCAF");
		var artworkTableHeader = document.getElementById("tableArtwork");
		var awardTableHeader = document.getElementById("tableAward");
		const descriptionEl = document.getElementById("description");
		let strSubtype = descriptionEl ? descriptionEl.innerHTML : "";

		// view type ex: table, gallerey
		if (view_button == tableViewSelected) {
		    if (strSubtype.includes("Learning")) {
		        var tableCatArtPapers = document.getElementById("category1");
				var tableCatCourses = document.getElementById("category2");
		        if (category_button == tableCatCourses) {
					coursesTableHeader.style.display = "inline-block";
					artPapersTableHeader.style.display = "none";
					learningTableHeader.style.display = "none";
					experienceTableHeader.style.display = "none";
					publicationTableHeader.style.display = "none";
					cafTableHeader.style.display = "none";
					artworkTableHeader.style.display = "none";
					awardTableHeader.style.display = "none";
				}
				else if (category_button == tableCatArtPapers) {
					artPapersTableHeader.style.display = "inline-block";
					coursesTableHeader.style.display = "none";
					learningTableHeader.style.display = "none";
					experienceTableHeader.style.display = "none";
					publicationTableHeader.style.display = "none";
					cafTableHeader.style.display = "none";
					artworkTableHeader.style.display = "none";
					awardTableHeader.style.display = "none";
				}
				else {
			        learningTableHeader.style.display = "inline-block";
					coursesTableHeader.style.display = "none";
					artPapersTableHeader.style.display = "none";
					experienceTableHeader.style.display = "none";
					publicationTableHeader.style.display = "none";
					cafTableHeader.style.display = "none";
					artworkTableHeader.style.display = "none";
					awardTableHeader.style.display = "none";
				}
		    }
			else if ((strSubtype.includes("Experiences")) 
					 || (strSubtype.includes("ACM SIGGRAPH Village"))
					 || (strSubtype.includes("Appy Hour")) 
					 || (strSubtype.includes("Birds of a Feather")) 
					 || (strSubtype.includes("Competitions"))
					 || (strSubtype.includes("Emerging Technologies")) 
					 || (strSubtype.includes("Games")) 
					 || (strSubtype.includes("History")) 
					 || (strSubtype.includes("Labs-Studio")) 
					 || (strSubtype.includes("Performances")) 
					 || (strSubtype.includes("Real-Time Live!"))
					 || (strSubtype.includes("SIGKids"))
				 	 || (strSubtype.includes("Special Sessions")) 
					 || (strSubtype.includes("SIGGRAPH Mobile")) 					 
					 || (strSubtype.includes("VR Theater")) 
					 || (strSubtype.includes("VR Experiences"))) 

			{
			    experienceTableHeader.style.display = "inline-block";
				learningTableHeader.style.display = "none";
			    coursesTableHeader.style.display = "none";
				artPapersTableHeader.style.display = "none";
				publicationTableHeader.style.display = "none";
				cafTableHeader.style.display = "none";
				artworkTableHeader.style.display = "none";
				awardTableHeader.style.display = "none";
			}
			else if (strSubtype.includes("Courses")) {
				coursesTableHeader.style.display = "inline-block";
				artPapersTableHeader.style.display = "none";
				learningTableHeader.style.display = "none";
				experienceTableHeader.style.display = "none";
				publicationTableHeader.style.display = "none";
				cafTableHeader.style.display = "none";
				artworkTableHeader.style.display = "none";
				awardTableHeader.style.display = "none";
			}
			else if (strSubtype.includes("Art Papers and Presentations")) {
			    artPapersTableHeader.style.display = "inline-block";
				coursesTableHeader.style.display = "none";
				learningTableHeader.style.display = "none";
				experienceTableHeader.style.display = "none";
				publicationTableHeader.style.display = "none";
				cafTableHeader.style.display = "none";
				artworkTableHeader.style.display = "none";
				awardTableHeader.style.display = "none";
			}
			else if ( 
					 (strSubtype.includes("Business")) ||					 
					 (strSubtype.includes("Co-located Events")) ||
					 (strSubtype.includes("Dailies")) ||
					 (strSubtype.includes("Diversity")) ||
					 (strSubtype.includes("Education")) || 
					 (strSubtype.includes("Exhibitor Sessions")) ||   
					 (strSubtype.includes("Frontiers")) || 
					 (strSubtype.includes("Keynotes")) || 
					 (strSubtype.includes("Panels")) || 
					 (strSubtype.includes("Posters")) || 
					 (strSubtype.includes("Production Sessions")) || 
					 (strSubtype.includes("Retrospective")) ||   
					 (strSubtype.includes("Talks-Sketches")) || 
					 (strSubtype.includes("Technical Papers")) || 
					 (strSubtype.includes("Web Graphics"))
					)  

			{
				learningTableHeader.style.display = "inline-block";
				coursesTableHeader.style.display = "none";
				artPapersTableHeader.style.display = "none";
				experienceTableHeader.style.display = "none";
				publicationTableHeader.style.display = "none";
				cafTableHeader.style.display = "none";
				artworkTableHeader.style.display = "none";
				awardTableHeader.style.display = "none";
			}
			else if ((strSubtype.includes("Publications")) || (strSubtype.includes("Animation Publication")) || (strSubtype.includes("Art Show Publication")) || (strSubtype.includes("Computer Graphics Quarterly")) || (strSubtype.includes("Conference Program / Information")) || (strSubtype.includes("Conference-Related Electronic Media")) || (strSubtype.includes("Experience Publication")) || (strSubtype.includes("L Pub")) || (strSubtype.includes("Proceeding")) || (strSubtype.includes("SIGGRAPHITTI Newsletter")) || (strSubtype.includes("Slide Set")) || (strSubtype.includes("Animation Booklet")) || (strSubtype.includes("AnimElectronicArt")) || (strSubtype.includes("Film Video Show")) || (strSubtype.includes("AnimVisPro")) || (strSubtype.includes("Art Show Catalog")) || (strSubtype.includes("Art Electronic Art")) || (strSubtype.includes("Leonardo")) || (strSubtype.includes("ArtVisPro")) || (strSubtype.includes("Advance Program")) || (strSubtype.includes("Call for Participation")) || (strSubtype.includes("Conference Locator")) || (strSubtype.includes("Final Program")) || (strSubtype.includes("Abstract App CDROM")) || (strSubtype.includes("Presentation DVD")) || (strSubtype.includes("Conference Select")) || (strSubtype.includes("Pubcourse")) || (strSubtype.includes("Full Conference")) || (strSubtype.includes("ConferenceRelVisPro")) || (strSubtype.includes("ExConAbApp")) || (strSubtype.includes("ExElecArtAnim")) || (strSubtype.includes("ExLeo")) || (strSubtype.includes("ExpVisPro")) || (strSubtype.includes("LearnConAbApp")) || (strSubtype.includes("LearnLeo")) || (strSubtype.includes("LearnProc")) || (strSubtype.includes("LearnVisPro")) || (strSubtype.includes("Computer Graphic")) || (strSubtype.includes("TOG")) || (strSubtype.includes("CGQ")) || (strSubtype.includes("App Design Tech")) || (strSubtype.includes("Slideartshow")) || (strSubtype.includes("Slideconference")) || (strSubtype.includes("Education")) || (strSubtype.includes("Industry Exhibition")) || (strSubtype.includes("Stereo")) || (strSubtype.includes("Technical"))) {
				publicationTableHeader.style.display = "inline-block";
				coursesTableHeader.style.display = "none";
				artPapersTableHeader.style.display = "none";
				learningTableHeader.style.display = "none";
				experienceTableHeader.style.display = "none";
				cafTableHeader.style.display = "none";
				artworkTableHeader.style.display = "none";
				awardTableHeader.style.display = "none";
			}
			else if (strSubtype.includes("CAF")) {
			    cafTableHeader.style.display = "inline-block";
				coursesTableHeader.style.display = "none";
				artPapersTableHeader.style.display = "none";
				learningTableHeader.style.display = "none";
				publicationTableHeader.style.display = "none";
				experienceTableHeader.style.display = "none";
				artworkTableHeader.style.display = "none";
				awardTableHeader.style.display = "none";
			}
			else if (strSubtype.includes("Artwork")) {
			    artworkTableHeader.style.display = "inline-block";
				cafTableHeader.style.display = "none";
				coursesTableHeader.style.display = "none";
				artPapersTableHeader.style.display = "none";
				learningTableHeader.style.display = "none";
				publicationTableHeader.style.display = "none";
				experienceTableHeader.style.display = "none";
				awardTableHeader.style.display = "none";
			}
			else if ((strSubtype.includes("Awards")) || (strSubtype.includes("Best Art Paper")) || (strSubtype.includes("Computer Animation Festival Awards")) || (strSubtype.includes("Computer Graphics Achievement Award")) || (strSubtype.includes("Distinguished Artist Award")) || (strSubtype.includes("Distinguished Educator Award")) || (strSubtype.includes("Electronic Theater Audience Choice")) || (strSubtype.includes("Electronic Theater Best in Show")) || (strSubtype.includes("Electronic Theater Best Student Project")) || (strSubtype.includes("Electronic Theater Jurys Choice")) || (strSubtype.includes("Outstanding Doctoral Dissertation Award")) || (strSubtype.includes("Outstanding Service Award")) || (strSubtype.includes("Practitioner Award")) || (strSubtype.includes("RTLive! Audience Choice")) || (strSubtype.includes("RTLive! Best in Show")) || (strSubtype.includes("Significant New Researcher Award")) || (strSubtype.includes("Steven Anson Coons Award"))) {
			    awardTableHeader.style.display = "inline-block";
				cafTableHeader.style.display = "none";
				coursesTableHeader.style.display = "none";
				artPapersTableHeader.style.display = "none";
				learningTableHeader.style.display = "none";
				publicationTableHeader.style.display = "none";
				experienceTableHeader.style.display = "none";
				artworkTableHeader.style.display = "none";
			}
		}
		else {
			learningTableHeader.style.display = "none";
			coursesTableHeader.style.display = "none";
			artPapersTableHeader.style.display = "none";
			experienceTableHeader.style.display = "none";
			publicationTableHeader.style.display = "none";
			cafTableHeader.style.display = "none";
			artworkTableHeader.style.display = "none";
			awardTableHeader.style.display = "none";
		}	
		// Final output
		document.getElementById("view").innerHTML = view_button.innerHTML;
		document.getElementById("sortby").innerHTML = sort_by_button.innerHTML;
		document.getElementById("sortstyle").innerHTML = sort_style_button.innerHTML;
		document.getElementById("year").innerHTML = year_button.innerHTML;
		document.getElementById("cat").innerHTML = category_button.innerHTML;
		//getEntries({view_button, sort_by_button, sort_style_button, year_button, category_button, page: 1});
		getEntries({page: 1});
    }
    function onPageClick(e, page, view_button, sort_by_button, sort_style_button, year_button, category_button) {
        e.preventDefault();
		// Gets the page number that was clicked on
		// and repasses the clicked-on button info back into the updated ajax call for new page
		getEntries({page, view_button, sort_by_button, sort_style_button, year_button, category_button});
		//getEntries({page, button});
    }
	// This is called each time by the filter and pagination buttons when they are clicked
	// Onclick determines all buttons that are active and sets the page number to one
	// Then, it runs the ajax call and displays the data
	function getEntries(props) {
	// Used for input entered into shortcode
	    const podNameEntry = jQuery('#pod_name_container').text().trim();
		const podSubtypeEntry = jQuery('#pod_sub_type_container').text().trim();
		const viewButtonEntry = jQuery('#view').text();
		const sortByButtonEntry = jQuery('#sortby').text();
		const sortStyleButtonEntry = jQuery('#sortstyle').text();
		const yearButtonEntry = jQuery('#year').text();
		const categoryButtonEntry = jQuery('#cat').text();
		// This function calls the ajax function with these parameters as props
		console.log('getEntries — prepared payload', {
		  pod_name: podNameEntry,
		  pod_sub_type: podSubtypeEntry,
		  view_button: viewButtonEntry,
		  sort_by_button: sortByButtonEntry,
		  sort_style_button: sortStyleButtonEntry,
		  year_button: yearButtonEntry,
		  category_button: categoryButtonEntry,
		  page: props.page || 1
		});
		loadFilteredEntries({...props, pod_name: podNameEntry, pod_sub_type: podSubtypeEntry, view_button: viewButtonEntry, sort_by_button: sortByButtonEntry, sort_style_button: sortStyleButtonEntry, year_button: yearButtonEntry, category_button: categoryButtonEntry, pod_sub_type: podSubtypeEntry});
    }
    jQuery(function() {
	    // Year buttons
		var a = document.createElement("div");
        a.id = "1973";
		a.classList.add("filter-buttons-dropdown-options-clicked");
        a.innerHTML = "View All";
		a.setAttribute("onclick", "onButtonClick('year', '1973', '1973')");
        document.getElementById("yearButtonsDropdown").appendChild(a);
		for (let i=CURRENT_CONFERENCE_YEAR; i >= FIRST_CONFERENCE_YEAR; i--) {
		    var b = document.createElement("div");
            b.id = i;
			b.classList.add("filter-buttons-dropdown-options");
            b.innerHTML = i;
			let para = "onButtonClick('year', 'aaa', 'aaa')";
			let repl = para.replace(/aaa/g, i);
			b.setAttribute("onclick", repl);
            document.getElementById("yearButtonsDropdown").appendChild(b);
		}
	    // Category buttons
		const descriptionEl = document.getElementById("description");
		let str = descriptionEl ? descriptionEl.innerHTML : "";
		if (str.includes("Learning")) {
		    const categoryTitlesLearning = ["Art Papers and Presentations", "Business", "Co-Located Events", "Courses", "Dailies", "Diversity", "Education", "Exhibitor Sessions", "Frontiers", "Keynotes", "Panels", "Posters", "Production Sessions", "Retrospective",  "Talks-Sketches", "Technical Papers", "Web Graphics"];
		    var y = document.createElement("div");
            y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            y.innerHTML = "View All";
			y.setAttribute("onclick", "onButtonClick('category', 'category0', 'category0')");
            document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1; i < (categoryTitlesLearning.length + 1); i++) {
				var x = document.createElement("div");
                x.id = "category" + i;
				x.classList.add("filter-buttons-dropdown-options");
                x.innerHTML = categoryTitlesLearning[i-1];
				let param = "onButtonClick('category', 'categoryaaa', 'categoryaaa')";
			    let repla = param.replace(/aaa/g, i);
			    x.setAttribute("onclick", repla);
                document.getElementById("categoryButtonsDropdown").appendChild(x);
			}
		}
		else if (str.match(/^Experiences$/)) {
			const categoryTitlesExperiences = ["ACM SIGGRAPH Village", "Appy Hour", "Birds of a Feather", "Competitions",   
							   "Emerging Technologies", "Games", "History", "Labs-Studio", "Performances",  
							   "Real-Time Live!", "SIGGRAPH Mobile", "SIGKids", "Special Sessions",  "VR Theater", "VR Experiences"];
		    var y = document.createElement("div");
            y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            y.innerHTML = "View All";
			y.setAttribute("onclick", "onButtonClick('category', 'category0', 'category0')");
            document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1; i < (categoryTitlesExperiences.length + 1); i++) {
				var x = document.createElement("div");
                x.id = "category" + i;
				x.classList.add("filter-buttons-dropdown-options");
                x.innerHTML = categoryTitlesExperiences[i-1];
				let param = "onButtonClick('category', 'categoryaaa', 'categoryaaa')";
				let repla = param.replace(/aaa/g, i);
			    x.setAttribute("onclick", repla);
                document.getElementById("categoryButtonsDropdown").appendChild(x);
			}
		}
		else if (str.includes("Publications")) {
			const categoryTitlesPublications = ["Animation Publication", "Art Show Publication", "Computer Graphics Quarterly", "Conference Program / Information", "Conference-Related Electronic Media", "Experience Publication", "Learning Publication", "Proceeding", "SIGGRAPHITTI Newsletter", "Slide Set"];
		    var y = document.createElement("div");
            y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            y.innerHTML = "View All";
			y.setAttribute("onclick", "onButtonClick('category', 'category0', 'category0')");
            document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1; i < (categoryTitlesPublications.length + 1); i++) {
				var x = document.createElement("div");
                x.id = "category" + i;
				x.classList.add("filter-buttons-dropdown-options");
                x.innerHTML = categoryTitlesPublications[i-1];
				let param = "onButtonClick('category', 'categoryaaa', 'categoryaaa')";
				let repla = param.replace(/aaa/g, i);
			    x.setAttribute("onclick", repla);
                document.getElementById("categoryButtonsDropdown").appendChild(x);
			}
		}
		else if(str.includes("Community")){
			const categoryTitlesCommunity = ["ACM SIGGRAPH Organization", "Pioneers", "Digital Arts Community", "Education Committee", "History and Archives Committee", "Professional and Student Chapters"];
		    var y = document.createElement("div");
            y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            y.innerHTML = "View All";
			y.setAttribute("onclick", "onButtonClick('category', 'category0', 'category0')");
            document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1; i < (categoryTitlesCommunity.length + 1); i++) {
				var x = document.createElement("div");
                x.id = "category" + i;
				x.classList.add("filter-buttons-dropdown-options");
                x.innerHTML = categoryTitlesCommunity[i-1];
				let param = "onButtonClick('category', 'categoryaaa', 'categoryaaa')";
				let repla = param.replace(/aaa/g, i);
			    x.setAttribute("onclick", repla);
                document.getElementById("categoryButtonsDropdown").appendChild(x);
			}
			// Default to Pioneers on the dedicated Community: Pioneers page
			// if (str.indexOf("Community: Pioneers") !== -1) {
			// 	var catSpan = document.getElementById("cat");
			// 	if (catSpan) {
			// 		catSpan.innerHTML = "Pioneers";
			// 		if (typeof window.getEntries === 'function') {
			// 			window.getEntries({ page: 1 });
			// 		}
			// 	}
			// }
		}
		else if (str.includes("Awards")) {
			const categoryTitlesAwards = ["Best Art Paper", "Computer Animation Festival Awards", "Computer Graphics Achievement Award", "Distinguished Artist Award", "Distinguished Educator Award", "Electronic Theater Audience Choice", "Electronic Theater Best in Show", "Electronic Theater Best Student Project", "Electronic Theater Jurys Choice", "Outstanding Doctoral Dissertation Award", "Outstanding Service Award", "Practitioner Award", "Real-Time Live! Audience Choice", "Real-Time Live! Best in Show", "Significant New Researcher Award", "Steven Anson Coons Award"];
			var y = document.createElement("div");
            y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            y.innerHTML = "View All";
			y.setAttribute("onclick", "onButtonClick('category', 'category0', 'category0')");
            document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1; i < (categoryTitlesAwards.length + 1); i++) {
				var x = document.createElement("div");
                x.id = "category" + i;
				x.classList.add("filter-buttons-dropdown-options");
                x.innerHTML = categoryTitlesAwards[i-1];
				let param = "onButtonClick('category', 'categoryaaa', 'categoryaaa')";
				let repla = param.replace(/aaa/g, i);
			    x.setAttribute("onclick", repla);
                document.getElementById("categoryButtonsDropdown").appendChild(x);
			}
		}
		else if (str.includes("ACM SIGGRAPH Village")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
	            	y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "ACM SIGGRAPH Village";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// range: 2012-CURRENT_CONFERENCE_YEAR
			for (let i=2011; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}
		}
		else if (str.includes("Appy Hour")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Appy Hour";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2013; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Birds of a Feather")) {
			// jets 7-08-2024, added BOF
		    	var x = document.getElementById("mainButton7");
		    	var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Birds of a Feather"; // "Birds of a Feather" works for view all experiences
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// range: 1975 to present
			document.getElementById(FIRST_CONFERENCE_YEAR).style.display = "none";
		}
		else if (str.includes("Emerging Technologies")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Emerging Technologies";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1989; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    }
		}
		else if (str.includes("Performances")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Performances";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 4-19-2024, added 2011
			// range: 1977, 2002-2005, 2011 
			for (let i=1976; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=2001; i >= 1978; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=2010; i >= 2006; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=CURRENT_CONFERENCE_YEAR; i >= 2012; i--) {
				document.getElementById(i).style.display = "none";
		    	}		
		}
		else if (str.includes("Competitions")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Competitions";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2006; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=CURRENT_CONFERENCE_YEAR; i >= 2010; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Games")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Games";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 4-19-2024, added 1998
			// range: 1998, 2023, 2024 			// jets 4-19-2024, added 1998
			for (let i=1997; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=2022; i >= 1999; i--) {
				document.getElementById(i).style.display = "none";
			}
			for (let i=1997; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=2022; i >= 1999; i--) {
				document.getElementById(i).style.display = "none";
			}
		}
		else if (str.includes("History")) {
			var x = document.getElementById("mainButton7");
			var y = document.createElement("div");
    			y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
    			y.innerHTML = "History";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 4-19-2024, added 1998
			// range: 1998, 2023 
			for (let i=1997; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}	
			for (let i=2022; i >= 1999; i--) {
				document.getElementById(i).style.display = "none";
			}
			for (let i=CURRENT_CONFERENCE_YEAR; i>2023; i--){
				document.getElementById(i).style.display = "none";
			}
		}
		else if (str.includes("Real-Time Live!")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Real-Time Live!";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2008; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Special Sessions")) {
		    // jets 7-25-2024, added Special Sessions button
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Special Sessions";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// range: 1977, 1979; 1980, 1984, 1989, 1991, 1994, 1996-2005, 
			// 2008, 2013, 2015, 2017, 2019, 2021, 2023
			for (let i=1976; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			document.getElementById(1978).style.display = "none";
			for (let i=1983; i >= 1981; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=1988; i >= 1985; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			document.getElementById(1990).style.display = "none";
			document.getElementById(1992).style.display = "none";
			document.getElementById(1993).style.display = "none";
			document.getElementById(1995).style.display = "none";
			document.getElementById(2006).style.display = "none";
			document.getElementById(2007).style.display = "none";
			for (let i=2012; i >= 2009; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			document.getElementById(2014).style.display = "none";
			document.getElementById(2016).style.display = "none";
			document.getElementById(2018).style.display = "none";
			document.getElementById(2020).style.display = "none";
			document.getElementById(2022).style.display = "none";
			for (let i=CURRENT_CONFERENCE_YEAR; i>=2023; i--){
				document.getElementById(i).style.display = "none";
			}
		}
		else if (str.includes("Labs-Studio")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Labs-Studio";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1997; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("VR Theater")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "VR Theater";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2016; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("VR Experiences")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "VR Experiences";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1990; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		    	for (let i=1993; i >= 1992; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		    	for (let i=1997; i >= 1995; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		    	for (let i=2013; i >= 1999; i--) {
				document.getElementById(i).style.display = "none";
		    	}	
		}
		else if (str.includes("Co-Located Events")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Co-Located Events";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 5-03-2024 updated range
			// range: 2008, 2011, 2016
			for (let i=2007; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}		
			document.getElementById(2010).style.display = "none";		
			for (let i=2015; i >= 2012; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=CURRENT_CONFERENCE_YEAR; i >= 2017; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Courses")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Courses";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Dailies")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Dailies";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2009; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=CURRENT_CONFERENCE_YEAR; i >= 2016; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Diversity")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Diversity";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2018; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Art Papers and Presentations")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Art Papers and Presentations";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 4-26-2024, added range
			// range: 1982, 1983, 1986, 1989, 1990, 1999-CURRENT_CONFERENCE_YEAR			
			for (let i=1981; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}
			document.getElementById(1984).style.display = "none";
			document.getElementById(1985).style.display = "none";
			document.getElementById(1987).style.display = "none";
			document.getElementById(1988).style.display = "none";
			for (let i=1998; i >= 1991; i--) {
				document.getElementById(i).style.display = "none";
			}
		}
		else if (str.includes("Education")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Education";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 4-26-2024, added range
			// jets 5-28-2024, added 2016 to range
			// range: 1977, 1987-1991, 1995-1998, 2000-2007, 2015, 2017-CURRENT_CONFERENCE_YEAR
			// jets 5-03-2024 added 1999, 2016 and 2009
			for (let i=1976; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}
			for (let i=1986; i >= 1978; i--) {
				document.getElementById(i).style.display = "none";
			} 	
			for (let i=1994; i >= 1992; i--) {
				document.getElementById(i).style.display = "none";
			}
			for (let i=2014; i >= 2010; i--) {
				document.getElementById(i).style.display = "none";
			}
			document.getElementById(2008).style.display = "none";

			//document.getElementById(1999).style.display = "none";
			//document.getElementById(2016).style.display = "none";
		}
		else if (str.includes("Exhibitor Sessions")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Exhibitor Sessions";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Business")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Business";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2010; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}
			for (let i=2017; i >= 2014; i--) {
				document.getElementById(i).style.display = "none";
			}
			for (let i=CURRENT_CONFERENCE_YEAR; i >= 2020; i--) {
				document.getElementById(i).style.display = "none";
			}
		}	
		else if (str.includes("Frontiers")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Frontiers";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2017; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}
		}		
		else if (str.includes("Keynotes")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Keynotes";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 5-03-2024 updated range
			// range: 1979, 1987-present
			for (let i=1978; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}		
			for (let i=1986; i >= 1980; i--) {
				document.getElementById(i).style.display = "none";
			}			
		}
		else if (str.includes("Panels")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Panels";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 5-03-2024 updated range
			// range: all years except 1974 (FIRST_CONFERENCE_YEAR), 1978, and 2003
			// jets 7-08-2024 remove 1978
			document.getElementById(1978).style.display = "none";
			document.getElementById(FIRST_CONFERENCE_YEAR).style.display = "none";
			document.getElementById(2003).style.display = "none";
		}
		else if (str.includes("Posters")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Posters";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2003; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			document.getElementById(1977).style.display = "block";
			document.getElementById(1978).style.display = "block";
		}
		else if (str.includes("Production Sessions")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Production Sessions";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 5-03-2024 updated range
			// range: 2008 to present
			for (let i=2007; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}		
		}
		else if (str.includes("Retrospective")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Retrospective";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2020; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
			}
		}
		else if (str.includes("SIGGRAPH Mobile")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "SIGGRAPH Mobile";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 4-19-2024, added 2014
			// range: 2012-2014 updated to 2012-2013
			// jets 5-04-2024, deleted 2014
			for (let i=2011; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let j=2014; j <= CURRENT_CONFERENCE_YEAR; j++) {
				document.getElementById(j).style.display = "none";
		    	}
		}
		else if (str.includes("SIGKids")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "SIGKids";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1991; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}	
			for (let j=2004; j <= CURRENT_CONFERENCE_YEAR; j++) {
				document.getElementById(j).style.display = "none";
		    	}
			document.getElementById(1996).style.display = "none";
			document.getElementById(1997).style.display = "none";
			document.getElementById(2001).style.display = "none";
		}
		else if (str.includes("Talks-Sketches")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Talks-Sketches";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=1993; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Technical Papers")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Technical Papers";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Web Graphics")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Web Graphics";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			for (let i=2001; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
			for (let i=CURRENT_CONFERENCE_YEAR; i >= 2006; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("CAF")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "View All";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Artwork")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "View All";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
			// jets 4-19-2024, added 1980			
			for (let i=1979; i >= FIRST_CONFERENCE_YEAR; i--) {
				document.getElementById(i).style.display = "none";
		    	}
		}
		else if (str.includes("Animation Publication")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Animation Publication";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Art Show Publication")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Art Show Publication";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Computer Graphics Quarterly")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Computer Graphics Quarterly";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Conference Program / Information")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Conference Program / Information";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Conference-Related Electronic Media")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Conference-Related Electronic Media";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Experience Publication")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Experience Publication";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("L Pub")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Learning Publication";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Proceeding")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("SIGGRAPHITTI Newsletter")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "SIGGRAPHITTI Newsletter";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Slide Set")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Slide Set";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Animation Booklet")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Animation Booklet";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("AnimElectronicArt")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Animation Electronic Art / Animation";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("AnimVisPro")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Animation Visual Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Film Video Show")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Film & Video Show / Electronic Theater";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Art Show Catalog")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Art Show Catalog";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Art Electronic Art")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Art Show Electronic Art / Animation";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Leonardo")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Leonardo Art Paper / Catalog";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("ArtVisPro")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Art Show Visual Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Advance Program")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Advance Program";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Call for Participation")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Call for Participation";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Conference Locator")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Conference Locator / Exhibitor Guide";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Final Program")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Final Program / Buyers Guide";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Abstract App CDROM")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Abstract / Application CD-ROM";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Presentation DVD")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Conference Presentation DVD";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Conference Select")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Conference Select";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Pubcourse")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Course Publication";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Full Conference")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Full Conference";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("ConferenceRelVisPro")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Conference-Related Visual Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("ExConAbApp")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Experience Abstract / Application";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("ExElecArtAnim")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Experience Electronic Art / Animation";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("ExLeo")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Experience Leonardo";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("ExpVisPro")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Experience Visual Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("LearnConAbApp")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Learning Abstract / Application";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("LearnLeo")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Learning Leonardo";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("LearnProc")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Learning Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("LearnVisPro")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Learning Visual Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Computer Graphic")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Computer / Graphic";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("TOG")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Transaction on Graphics";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("CGQ")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
           		y.innerHTML = "Computer Graphics Quarterly Proceeding";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("App Design Tech")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Application / Design Technology";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Slideartshow")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Art Show Slide Set";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Slideconference")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
           		y.innerHTML = "Conference";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Education")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Education";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Industry Exhibition")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Industry / Exhibition";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Stereo")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Stereo";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Technical")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Technical";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Best Art Paper")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
            		y.innerHTML = "Best Art Paper";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Computer Animation Festival Awards")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Computer Animation Festival Awards";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("ComputerGraphicsAchievementAward")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Computer Graphics Achievement Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Distinguished Artist Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Distinguished Artist Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Distinguished Educator Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Distinguished Educator Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Electronic Theater Audience Choice")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Electronic Theater Audience Choice";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Electronic Theater Best in Show")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Electronic Theater Best in Show";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Electronic Theater Best Student Project")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Electronic Theater Best Student Project";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Electronic Theater Jurys Choice")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Electronic Theater Jurys Choice";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Outstanding Doctoral Dissertation Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Outstanding Doctoral Dissertation Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Outstanding Service Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Outstanding Service Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Practitioner Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Practitioner Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("RTLive! Audience Choice")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Real-Time Live! Audience Choice";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("RTLive! Best in Show")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Real-Time Live! Best in Show";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Significant New Researcher Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Significant New Researcher Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Steven Anson Coons Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Steven Anson Coons Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		else if (str.includes("Steven Anson Coons Award")) {
		    var x = document.getElementById("mainButton7");
		    var y = document.createElement("div");
            		y.id = "category0";
			y.classList.add("filter-buttons-dropdown-options-clicked");
			x.classList.add("one-line-title");
            		y.innerHTML = "Steven Anson Coons Award";
			x.innerHTML = y.innerHTML;
			document.getElementById("categoryButtonsDropdown").appendChild(y);
		}
		// Trigger click of first button
		//document.getElementById("mainButton0").trigger('onclick');
		// Default page load button clicks and entry display
		onButtonClick('view', 'mainButton0', 'mainButton1');
		console.log("Triggered default click");
    })


</script>
<?php
$filtration_mode = function_exists('siggraph_filtration_get_mode') ? siggraph_filtration_get_mode(false) : 'new';
?>
<div class="filtration-mode-toggle__wrapper">
	<div class="filtration-mode-toggle" data-current-mode="<?php echo esc_attr($filtration_mode); ?>">
		<span class="filtration-mode-toggle__label">Filtration mode</span>
		<button type="button" class="filtration-mode-toggle__button<?php echo $filtration_mode === 'old' ? ' is-active' : ''; ?>" data-mode="old" aria-pressed="<?php echo $filtration_mode === 'old' ? 'true' : 'false'; ?>">Classic</button>
		<button type="button" class="filtration-mode-toggle__button<?php echo $filtration_mode === 'new' ? ' is-active' : ''; ?>" data-mode="new" aria-pressed="<?php echo $filtration_mode === 'new' ? 'true' : 'false'; ?>">New</button>
	</div>
</div>
<script>
(function() {
	const toggle = document.querySelector('.filtration-mode-toggle');
	if (!toggle) return;

	toggle.addEventListener('click', function(event) {
		const btn = event.target.closest('[data-mode]');
		if (!btn) return;
		const mode = btn.getAttribute('data-mode');
		if (!mode) return;

		document.cookie = 'siggraph_filtration_mode=' + mode + '; path=/; max-age=' + (60 * 60 * 24 * 30);
		const url = new URL(window.location.href);
		url.searchParams.set('filtration_mode', mode);
		window.location.href = url.toString();
	});
})();
</script>

<!-- <style onload="loadDefaultElements()"></style> -->
<div>
<!-- Text above buttons, desc_name is used in the shortcode to fill in the text dynamically -->
<!-- <p style="max-width: 75%;">View <?= esc_html($args['desc_name'])?> entries by choosing from the buttons below.</p> -->
</div>
<br>
<div style="clear: both;"></div>
<!-- ************************* Filtration buttons v0.1 ************************* -->
<div class="sgg-layout">
  
  <!-- Left sidebar facets (config-driven later; static shells for now) -->
  <aside id="sgg-facets" class="sgg-facets">

  <!-- PUBLICATIONS -->
  <section data-group="Publications">

	<!-- Subtype Buttons -->
   <div class="sgg-facet">
      <div class="sgg-facet__title">Category</div>
        <div class="sgg-facet__body" id="facet-category">
        </div>
    </div>
	
	<div class="sgg-facet" id="category-type">
	  <h3 class="sgg-facet__title">Type</h3>
	  <!-- <div class="sgg-facet__body" id="facet-type"></div> -->
	  <div class="sgg-facet__body" id="facet-type">
		<?php
		// Configuration array for all categories and their types
$category_config = [
	'community' => [
		'pioneers' => [
			'paths' => ['/community-pioneers/'],
			'slugs' => ['community-pioneers']
		],
		'education' => [
			'paths' => ['/community-education-committee/', '/community-education/'],
			'slugs' => ['community-education-committee', 'community-education']
		],
		'chapters' => [
			'paths' => ['/community-professional-and-student-chapters-committee/', '/community-chapters/'],
			'slugs' => ['community-professional-and-student-chapters-committee', 'community-chapters']
		],
		'digital-arts' => [
			'paths' => ['/community-digital-arts-community/'],
			'slugs' => ['community-digital-arts-community']
		],
		'organization' => [
			'paths' => ['/community-acm-siggraph-organization/'],
			'slugs' => ['community-acm-siggraph-organization']
		],
		'history' => [
			'paths' => ['/community-history-and-archives/'],
			'slugs' => ['community-history-and-archives']
		]
	],
];

// Taxonomy mapping for each category - UPDATE THESE WITH YOUR ACTUAL TAXONOMY NAMES
$category_taxonomies = [
	'community' => ['community_item_event_types', 'education_item_type'],
	'experience' => ['competition_type', 'etech_type'],
	'learning' => 'learning_type',
	'publications' => 'publication_type',     
	'awards' => 'award_type',
];

// Map community subcategory types to their specific taxonomies
$community_type_taxonomy_map = [
	'pioneers' => 'community_item_event_types',
	'education' => 'education_item_type',
	// Add more mappings as needed for other community subcategories
];

$experience_type_taxonomy_map = [
	'competition' => 'competition_type',
	'etech' => 'etech_type',
	'game' => 'game_type',
	'history' => 'history_type',
	'performance' => 'performance_type',
	// Add more mappings as needed for other experience subcategories
];

$learning_type_taxonomy_map = [
	'art_paper' => 'art_paper_type',
	'frontier' => 'frontier_type',
];

// Function to detect current category and type (only for community navigation)
function detect_category_and_type($config, $current_path, $page_slug) {
	foreach ($config as $category => $types) {
		foreach ($types as $type => $patterns) {
			// Check paths
			foreach ($patterns['paths'] as $path) {
				if (strpos($current_path, $path) !== false) {
					return ['category' => $category, 'type' => $type];
				}
			}
			// Check slugs
			if (in_array($page_slug, $patterns['slugs'])) {
				return ['category' => $category, 'type' => $type];
			}
		}
	}
	return null;
}

// Function to render subcategory radio buttons
// This function determines the taxonomy based on the category/type, just like the JavaScript does
function render_subcategory_radios($category_name, $type = null) {
	// Map community subcategory types to their specific taxonomies (same as JavaScript)
	$community_type_taxonomy_map = [
		'pioneers' => 'community_item_event_types',
		'education' => 'education_item_type',
		// Add more mappings as needed for other community subcategories
	];
	// $experience_type_taxonomy_map = [
	// 	'competition' => 'competition_type',
	// 	'etech' => 'etech_type',
	// 	'game' => 'game_type',
	// 	'history' => 'history_type',
	// 	'keyword' => 'keyword',
	// 	'interest_area' => 'interest_area',
	// 	'lab' => 'lab_type',
	// 	'performance' => 'performance_type',
		
	// 	// Add more mappings as needed for other experience subcategories
	// ];
	
	// Map category names to radio button names (must match JavaScript categoryRadioMap)
	$category_radio_map = [
		'community' => 'community-subcategory',
		'experience' => 'experience-subcategory',
		'learning' => 'learning-subcategory',
		'publications' => 'publications-subcategory',
		'awards' => 'awards-subcategory'
	];
	
	// Get the radio button name for this category
	$radio_name = $category_radio_map[$category_name] ?? 'community-subcategory';
	$radio_id_prefix = str_replace('-subcategory', '-subcat', $radio_name);
	
	// Determine which taxonomy to use based on category and type
	$taxonomy = null;
	
	if ($category_name === 'community' && $type) {
		// For community, use the type-specific taxonomy mapping
		$taxonomy = $community_type_taxonomy_map[$type] ?? null;
	} 
	else if ($category_name === 'experience' && $type) {
		// For experience, use the type-specific taxonomy mapping
		$taxonomy = $experience_type_taxonomy_map[$type] ?? null;

	}
	else if ($category_name === 'learning' && $type) {
		// For learning, use the type-specific taxonomy mapping
		$taxonomy = $learning_type_taxonomy_map[$type] ?? null;

	}
	else {
		// For other categories, use the category_taxonomies mapping
		// Access the global category_taxonomies array
		global $category_taxonomies;
		if (!isset($category_taxonomies)) {
			$category_taxonomies = [
				'community' => 'community',
				'experience' => 'experience_type',
				'learning' => 'learning_type',
				'publications' => 'publication_type',     
				'awards' => 'award_type',
			];
		}
		$taxonomy_config = $category_taxonomies[$category_name] ?? null;
		
		if (is_array($taxonomy_config)) {
			// If it's an array, use the first one (or could use type if provided)
			$taxonomy = !empty($taxonomy_config) ? $taxonomy_config[0] : null;
		} else {
			// If it's a string, use it directly
			$taxonomy = $taxonomy_config;
		}
	}
	
	if (!$taxonomy) {
		// If no taxonomy found, still show "View All" option
		echo '<label class="sgg-radio"><input type="radio" name="' . esc_attr($radio_name) . '" id="' . esc_attr($radio_id_prefix) . '-all" value="all" checked> View All Types</label>';
		return;
	}
	
	$subtypes = get_terms([
		'taxonomy' => $taxonomy,
		'hide_empty' => false,
		'orderby' => 'name',
		'order' => 'ASC'
	]);
	
	if (!is_wp_error($subtypes) && !empty($subtypes)) {
		// Add "View All" option first
		echo '<label class="sgg-radio"><input type="radio" name="' . esc_attr($radio_name) . '" id="' . esc_attr($radio_id_prefix) . '-all" value="all" checked> View All Types</label>';
		
		// Add each subcategory
		foreach ($subtypes as $term) {
			$id = esc_attr($radio_id_prefix) . '-' . esc_attr($term->slug);
			echo '<label class="sgg-radio"><input type="radio" name="' . esc_attr($radio_name) . '" id="' . $id . '" value="' . esc_attr($term->slug) . '"> ' . esc_html($term->name) . '</label>';
		}
	} else {
		// If no terms found, still show "View All" option
		echo '<label class="sgg-radio"><input type="radio" name="' . esc_attr($radio_name) . '" id="' . esc_attr($radio_id_prefix) . '-all" value="all" checked> View All Types</label>';
	}
}

// Main execution
$current_url = $_SERVER['REQUEST_URI'] ?? '';
$current_path = parse_url($current_url, PHP_URL_PATH);
$page_slug = get_post_field('post_name', get_the_ID());
$pod_name = isset($args['pod_name']) ? $args['pod_name'] : '';

// First, try to detect community subcategory pages via path/slug
$detected = detect_category_and_type($category_config, $current_path, $page_slug);

// If not on a community subcategory page, check if we're on any main category page via pod_name
if (!$detected && !empty($pod_name)) {
	// Map pod_name to category
	$pod_to_category = [
		'experience' => 'experience',
		'learning' => 'learning',
		'publication' => 'publications',  // Note: pod_name is 'publication' but category is 'publications'
		'award' => 'awards',              // Note: pod_name is 'award' but category is 'awards'
		'community' => 'community'
	];
	
	if (isset($pod_to_category[$pod_name])) {
		$category = $pod_to_category[$pod_name];
		$detected = ['category' => $category, 'type' => 'main'];
	}
}

// Only populate Type facet for Community subcategory pages on initial load
// For other pages (Experience, Learning, Publications, Awards), JavaScript will populate this dynamically
if ($detected && $detected['category'] === 'community') {
	$category = $detected['category'];
	$type = $detected['type'] ?? null;
	
	// Render the radio buttons - function will determine taxonomy based on category and type
	render_subcategory_radios($category, $type);
}
// For non-Community pages, leave the facet empty - JavaScript will populate it when a category is selected
		?>
	  </div>
	</div>

	<!-- <div class="sgg-facet" id="category-sub-type">
		<h3 class="sgg-facet__title">Sub Type</h3>
		<div class="sgg-facet__body" id="facet-sub-type"></div>
	</div> -->
		
    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Year</h3>
      <div class="sgg-facet__body" id="facet-conf_year"></div>
    </div>
	<!--
    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Conference Type</h3>
      <div class="sgg-facet__body" id="facet-conf_type">
        <label><input type="checkbox" value="siggraph"> SIGGRAPH</label><br>
        <label><input type="checkbox" value="siggraph-asia"> SIGGRAPH Asia</label>
      </div>
    </div> -->
  </section>

  <!-- COLLECTIBLES
  <section data-group="collectibles">
    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Subtype</h3>
      <div class="sgg-facet__body" id="facet-collectible_type"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Condition</h3>
      <div class="sgg-facet__body" id="facet-condition"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Donated By</h3>
      <div class="sgg-facet__body" id="facet-donor"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Conference Year</h3>
      <div class="sgg-facet__body" id="facet-conf_year_collectibles"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Conference Type</h3>
      <div class="sgg-facet__body">
        <label><input type="checkbox" value="siggraph"> SIGGRAPH</label><br>
        <label><input type="checkbox" value="siggraph-asia"> SIGGRAPH Asia</label>
      </div>
    </div>
  </section>

  
  // COMMUNITY 
  <section data-group="community">
    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Community Type</h3>
      <div class="sgg-facet__body" id="facet-community_type"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Conference Year</h3>
      <div class="sgg-facet__body" id="facet-conf_year_community"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Conference Type</h3>
      <div class="sgg-facetz__body">
        <label><input type="checkbox" value="siggraph"> SIGGRAPH</label><br>
        <label><input type="checkbox" value="siggraph-asia"> SIGGRAPH Asia</label>
      </div>
    </div>
  </section>

  // COMMUNITY OVERVIEW 
  <section data-group="community_overview">
    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Community Type</h3>
      <div class="sgg-facet__body" id="facet-community_overview_type"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Conference Year</h3>
      <div class="sgg-facet__body" id="facet-conf_year_community_overview"></div>
    </div>

    <div class="sgg-facet">
      <h3 class="sgg-facet__title">Conference Type</h3>
      <div class="sgg-facet__body">
        <label><input type="checkbox" value="siggraph"> SIGGRAPH</label><br>
        <label><input type="checkbox" value="siggraph-asia"> SIGGRAPH Asia</label>
      </div>
    </div>
  </section> 
-->

</aside>

  <!-- Main column (toolbar + existing headers/results/pagination) -->
  <main class="sgg-results">
    <div class="sgg-toolbar">
      <input id="sgg-q" class="sgg-input" type="search"
            placeholder="Search title/author/year" aria-label="Search">

      <label class="sgg-sort">
        <span class="sgg-sort__label"> </span>	<!-- Add "Sort by" between "> <" if necessary -->
        <select id="sgg-sort-select" aria-label="Sort by">
          <option value="title_asc">Title (A–Z)</option>
          <option value="title_desc">Title (Z–A)</option>
          <option value="year_asc">Year (Ascending)</option>
          <option value="year_desc" selected>Year (Descending)</option>
        </select>
      </label>

      <div class="sgg-view" role="group" aria-label="View style">
        <button class="sgg-view-btn" data-view="grid" aria-pressed="true" title="Grid view">▦</button>
        <button class="sgg-view-btn" data-view="list" aria-pressed="false" title="List view">≡</button>
      </div>
    </div>
<!-- ************************* End Filtration buttons ************************* -->
<br/>
<div id="tableContainerScroll">
<!-- Table headers -->
<div id="tableLearning" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex; max-width:1300px; min-width:765px;">
    <div id="learningHeader1" style="float:left; width:10%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div id="learningHeader2" style="float:left; width:5%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>No.</strong></div>
    <div id="learningHeader3" style="float:left; width:37%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Title</strong></div>
    <div id="learningHeader4" style="float:left; width:35%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Presenter(s)</strong></div>
    <div id="learningHeader5" style="float:left; width:13%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Learning Type</strong></div>
</div>
<div id="tableArtPapers" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">		
    <div id="artPapersHeader1" style="float:left; width:12%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div id="artPapersHeader2" style="float:left; width:40%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Title</strong></div>
    <div id="artPapersHeader3" style="float:left; width:35%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Presenter(s)</strong></div>
    <div id="artPapersHeader4" style="float:left; width:13%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Art Paper Type</strong></div>
</div>
<div id="tableCourses" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">		
    <div id="coursesHeader1" style="float:left; width:8%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div id="coursesHeader2" style="float:left; width:5%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>No.</strong></div>
    <div id="coursesHeader3" style="float:left; width:25%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Title</strong></div>
    <div id="coursesHeader4" style="float:left; width:13%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Chair(s)</strong></div>
    <div id="coursesHeader5" style="float:left; width:27%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Presenter(s)</strong></div>
    <div id="coursesHeader6" style="float:left; width:22%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Location</strong></div>
</div>
<div id="tableExperience" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">		
    <div id="experienceHeader1" style="float:left; width:10%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div id="experienceHeader2" style="float:left; width:5%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>No.</strong></div>
    <div id="experienceHeader3" style="float:left; width:35%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Title</strong></div>
    <div id="experienceHeader4" style="float:left; width:35%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Presenter(s)</strong></div>
    <div id="experienceHeader5" style="float:left; width:15%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Experience Type</strong></div>
</div>
<div id="tablePublication" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">		
    <div id="publicationHeader1" style="float:left; width:17%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Publication Type</strong></div>
    <div id="publicationHeader2" style="float:left; width:38%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Publication Title</strong></div>
    <div id="publicationHeader3" style="float:left; width:10%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Date</strong></div>
	<div id="publicationHeader4" style="float:left; width:4%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Vol.</strong></div>
	<div id="publicationHeader5" style="float:left; width:4%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>No.</strong></div>
    <div id="publicationHeader6" style="float:left; width:10%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div id="publicationHeader7" style="float:left; width:17%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Location</strong></div>
</div>
<div id="tableCAF" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">		
    <div id="cafHeader1" style="float:left; width:15%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div id="cafHeader2" style="float:left; width:32%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Title</strong></div>
	<div id="cafHeader3" style="float:left; width:25%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Director(s)</strong></div>
    <div id="cafHeader4" style="float:left; width:23%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Company/Institution/Agency</strong></div>
	<div id="cafHeader5" style="float:left; width:5%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Image</strong></div>
</div>
<div id="tableArtwork" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">
    <div id="artworkHeader1" style="float:left; width:14%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Exhibition</strong></div>
    <div id="artworkHeader2" style="float:left; width:40%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Title</strong></div>
	<div id="artworkHeader3" style="float:left; width:40%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Artist(s)</strong></div>
	<div id="artworkHeader5" style="float:left; width:6%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Image</strong></div>
</div>
<div id="tableCommunity" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">
    <div style="float:left; width:20%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div style="float:left; width:50%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Title</strong></div>
    <div style="float:left; width:30%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Community Type</strong></div>
</div>
<div id="tableAward" style="display:none; background-color:hsl(195, 30%, 92%); border: 1px solid #d4d2d1; width:100%; display: flex;">		
    <div id="awardHeader1" style="float:left; width:15%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Conference</strong></div>
    <div id="awardHeader2" style="float:left; width:25%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Award Type</strong></div>
    <div id="awardHeader3" style="float:left; width:30%; text-align:left; padding:1px 10px 1px 10px; border-right: 1px solid #d4d2d1; overflow: hidden;"><strong>Awardee(s)</strong></div>
	<div id="awardHeader4" style="float:left; width:30%; text-align:left; padding:1px 10px 1px 10px; overflow: hidden;"><strong>Awarded Work Title</strong></div>
</div>
<!-- Where the ajax call puts the entries -->
<div id="all-filtered-entries" style="color:black"></div>
<!-- Pagination under the entries -->
<span id="entry-pagination"></span>
<span id="view" style="display:none;"></span>
<span id="sortby" style="display:none;"></span>
<span id="sortstyle" style="display:none;"></span>
<span id="year" style="display:none;"></span>
<span id="cat" style="display:none;"></span>
<span id="q" style="display:none;"></span>
<!-- Input from shortcode for pod name -->
<span id="pod_name_container" style="display:none;">
    <?php 
			if (isset($args['pod_name'])) {
				$podName = $args['pod_name'];
				echo htmlspecialchars($podName);
			}
	?>
</span>
<!-- Input from shortcode for pod subtype name -->
<span id="pod_sub_type_container" style="display:none;">
	<?php
		if (isset($args['pod_sub_type'])) {
			$podSubType = $args['pod_sub_type'];
			echo htmlspecialchars($podSubType);
		}
	?>
</span>

<!-- Populate facets based on pod_name -->


<!-- Pass taxonomy data to JavaScript for dynamic category and subcategory options -->
<script>
window.FiltrationTaxonomies = window.FiltrationTaxonomies || {};
<?php
// Get Learning types
$learning_terms = get_terms([
    'taxonomy' => 'learning_type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($learning_terms) && !empty($learning_terms)) {
    $learning_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $learning_terms);
    echo 'window.FiltrationTaxonomies.learningTypes = ' . wp_json_encode($learning_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.learningTypes = [];';
}

// Get Experience types
$experience_terms = get_terms([
    'taxonomy' => 'experience_type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($experience_terms) && !empty($experience_terms)) {
    $experience_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $experience_terms);
    echo 'window.FiltrationTaxonomies.experienceTypes = ' . wp_json_encode($experience_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.experienceTypes = [];';
}

// Get Community types
$community_terms = get_terms([
    'taxonomy' => 'community_type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($community_terms) && !empty($community_terms)) {
    $community_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $community_terms);
    echo 'window.FiltrationTaxonomies.communityTypes = ' . wp_json_encode($community_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.communityTypes = [];';
}

// Get Publication types
$publication_terms = get_terms([
    'taxonomy' => 'publication_type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($publication_terms) && !empty($publication_terms)) {
    $publication_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $publication_terms);
    echo 'window.FiltrationTaxonomies.publicationTypes = ' . wp_json_encode($publication_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.publicationTypes = [];';
}

// Get Publication sub-types
$publication_sub_type_terms = get_terms([
    'taxonomy' => 'publication_sub-type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($publication_sub_type_terms) && !empty($publication_sub_type_terms)) {
    $publication_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $publication_sub_type_terms);
    echo 'window.FiltrationTaxonomies.publicationSubTypes = ' . wp_json_encode($publication_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.publicationSubTypes = [];';
}


// Get Award types
$award_terms = get_terms([
    'taxonomy' => 'award_type',
    'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($award_terms) && !empty($award_terms)) {
    $award_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $award_terms);
    echo 'window.FiltrationTaxonomies.awardTypes = ' . wp_json_encode($award_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.awardTypes = [];';
}

// Get Collectible types
$collectible_terms = get_terms([
    'taxonomy' => 'collectible_type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($collectible_terms) && !empty($collectible_terms)) {
    $collectible_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $collectible_terms);
    echo 'window.FiltrationTaxonomies.collectibleTypes = ' . wp_json_encode($collectible_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.collectibleTypes = [];';
}

// Community sub-types can vary based on the subcategory (e.g., Pioneers, Education, etc.)
// Get Pioneers sub-types (community_item_event_types)
$pioneer_subtypes = get_terms([
    'taxonomy' => 'community_item_event_types',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($pioneer_subtypes) && !empty($pioneer_subtypes)) {
    $pioneer_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $pioneer_subtypes);
    echo 'window.FiltrationTaxonomies.pioneerSubtypes = ' . wp_json_encode($pioneer_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.pioneerSubtypes = [];';
}

$education_subtypes = get_terms([
    'taxonomy' => 'education_item_type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);
if (!is_wp_error($education_subtypes) && !empty($education_subtypes)) {
    $education_data = array_map(function($term) {
        return ['slug' => $term->slug, 'name' => $term->name];
    }, $education_subtypes);
    echo 'window.FiltrationTaxonomies.educationSubtypes = ' . wp_json_encode($education_data) . ';';
} else {
    echo 'window.FiltrationTaxonomies.educationSubtypes = [];';
}


// ---------------------------------------------------------------------------------------
// Experience sub-types can vary based on the subcategory (e.g., Competition, ETech, etc.)
// ---------------------------------------------------------------------------------------
$competition_subtypes = get_terms([
	'taxonomy' => 'competition_type',
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($competition_subtypes) && !empty($competition_subtypes)) {
	$competition_data = array_map(function($term) {
		return ['slug' => $term->slug, 'name' => $term->name];
	}, $competition_subtypes);
	echo 'window.FiltrationTaxonomies.competitionSubtypes = ' . wp_json_encode($competition_data) . ';';
} else {
	echo 'window.FiltrationTaxonomies.competitionSubtypes = [];';
}

$etech_subtypes = get_terms([
	'taxonomy' => 'etech_type',
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($etech_subtypes) && !empty($etech_subtypes)) {
	$etech_data = array_map(function($term) {
		return ['slug' => $term->slug, 'name' => $term->name];
	}, $etech_subtypes);
	echo 'window.FiltrationTaxonomies.etechSubtypes = ' . wp_json_encode($etech_data) . ';';
} else {
	echo 'window.FiltrationTaxonomies.etechSubtypes = [];';
}

$game_subtypes = get_terms([
	'taxonomy' => 'game_type',
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($game_subtypes) && !empty($game_subtypes)) {
	$game_data = array_map(function($term) {
		return ['slug' => $term->slug, 'name' => $term->name];
	}, $game_subtypes);
	echo 'window.FiltrationTaxonomies.gameSubtypes = ' . wp_json_encode($game_data) . ';';
} else {
	echo 'window.FiltrationTaxonomies.gameSubtypes = [];';
}
$history_subtypes = get_terms([
	'taxonomy' => 'history_type',
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($history_subtypes) && !empty($history_subtypes)) {
	$history_data = array_map(function($term) {
		return ['slug' => $term->slug, 'name' => $term->name];
	}, $history_subtypes);
	echo 'window.FiltrationTaxonomies.historySubtypes = ' . wp_json_encode($history_data) . ';';
} else {
	echo 'window.FiltrationTaxonomies.historySubtypes = [];';
}

$performance_subtypes = get_terms([
	'taxonomy' => 'performance_type',
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($performance_subtypes) && !empty($performance_subtypes)) {
	$performance_data = array_map(function($term) {
		return ['slug' => $term->slug, 'name' => $term->name];
	}, $performance_subtypes);
	echo 'window.FiltrationTaxonomies.performanceSubtypes = ' . wp_json_encode($performance_data) . ';';
} else {
	echo 'window.FiltrationTaxonomies.performanceSubtypes = [];';
}

// -------------------------------------------------------
// Learning sub-types can vary based on the subcategory
// -------------------------------------------------------
$art_paper_subtypes = get_terms([
	'taxonomy' => 'art_paper_type',
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($art_paper_subtypes) && !empty($art_paper_subtypes)) {
	$art_paper_data = array_map(function($term) {
		return ['slug' => $term->slug, 'name' => $term->name];
	}, $art_paper_subtypes);
	echo 'window.FiltrationTaxonomies.artPaperSubtypes = ' . wp_json_encode($art_paper_data) . ';';
} else {
	echo 'window.FiltrationTaxonomies.artPaperSubtypes = [];';
}

$frontier_subtypes = get_terms([
	'taxonomy' => 'frontier_type',
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC'
]);
if (!is_wp_error($frontier_subtypes) && !empty($frontier_subtypes)) {
	$frontier_data = array_map(function($term) {
		return ['slug' => $term->slug, 'name' => $term->name];
	}, $frontier_subtypes);
	echo 'window.FiltrationTaxonomies.frontierSubtypes = ' . wp_json_encode($frontier_data) . ';';
} else {
	echo 'window.FiltrationTaxonomies.frontierSubtypes = [];';
}
?>
</script>
</div>
