// DisableNewConvoEmailPerMailbox.js by Jeff Sherk
// Some Javascript

var thePath = window.location.pathname;

// make sure we are in the Manager Users directory
if ( thePath.substring(0, 7) == "/users/" ) {
    var userDir = thePath.substring( 0, thePath.lastIndexOf('/') );
    var userID = thePath.substring( thePath.lastIndexOf('/')+1 ); // we can get the user_id from the end of the URL
    
    if (userDir == "/users/profile" || userDir == "/users/permissions" || userDir == "/users/notifications") {
        
        more_notifications_page = "notifications";
        more_notifications_link_text = "More Notifications (beta)";
        
        // setup new menu item
        var menu_item_li = document.createElement("li");
        menu_item_li.innerHTML = "<a href='/users/"+more_notifications_page+"/"+userID+"'><i class='glyphicon glyphicon-bell'></i>"+more_notifications_link_text+"</a>";
        menu_item_li.setAttribute("id", "more-notifications"); // added line
        //menu_item_li.className = 'treehtml';
        
        // add menu item to sidebar
        var sidebar_menu_uls = document.getElementsByClassName("sidebar-menu"); // get all UL's with this class name
        var sidebar_menu_ul = Object.values(sidebar_menu_uls)[0]; // there should only be one UL with sidebar-menu class, just get the first element
        sidebar_menu_ul.appendChild(menu_item_li); // append the LI to the UL
        
        // console.log(sidebar_menu_ul); //DEBUG only
    } 

}