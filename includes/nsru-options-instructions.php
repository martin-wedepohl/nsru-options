<div class="wrap">
    <h2>North Shore Round Up Plugin Instructions</h2>
    
    <p>View instructions on how to use the North Shore Round Up Plugin, please click the title below to view each section</p>
    
    <div class="tab">
        <button id="OverviewButton" class="tablinks" onclick="openTab(event, 'Overview')">Overview</button>
        <button class="tablinks" onclick="openTab(event, 'Theme')">Theme</button>
        <button class="tablinks" onclick="openTab(event, 'Settings')">Settings</button>
        <button class="tablinks" onclick="openTab(event, 'Shortcodes')">Shortcodes</button>
        <button class="tablinks" onclick="openTab(event, 'Types')">Types</button>
        <button class="tablinks" onclick="openTab(event, 'Pages')">Pages</button>
    </div>
    
    
    <div id="Overview" class="tabcontent active">
        <p>
            The North Shore Round Up Options is used by the North Shore Round Up Child Theme which is
            based on the Elementor Layers Child Theme.
        </p>
        <p>
            For the site to work using the NSRU Theme and Plugin the following are required.
        </p>
        <h3>Themes</h3>
        <ul class="wantdisc">
            <li>layers-elementor - Light weight theme that works well with the Elementor plugin</li>
            <li>nsru-theme - Customized child theme of layers-elementor</li>
        </ul>
        <h3>Plugins</h3>
        <ul class="wantdisc">
            <li>Autoptimize - Optimizes CSS, JS, Fonts to increase site speed (Recommended)</li>
            <li>Classic Editor - Enable the old style (non-block) editor (Recommended)</li>
            <li>Contact Form 7 - Create Contact Forms (Recommended)</li>
            <li>Contact Form 7 MailChimp Extension - Integrate Contact Form 7 with MailChimp (Recommended)</li>
            <li>Duplicator - Backup/Migrate (Recommended)</li>
            <li>Elementor - Drag and Drop page builder <strong>(REQUIRED)</strong></li>
            <li>NSRU Options Plugin - Options customized for NSRU Theme <strong>(REQUIRED)</strong></li>
            <li>Smush - Image compression (Recommended)</li>
            <li>WP Super Cache - WordPress caching (Recommended)</li>
            <li>Yoast SEO - All in one SEO (Recommended)</li>
        </ul>
    </div><!-- #Overview -->
    

    <div id="Theme" class="tabcontent">
        
        <p>
            The North Shore Round Up Theme is a child theme of the Layers theme provided by Elementor.
            Most of the setting can be customized from the Theme Customizer.
        </p>
        <p>
            All pages <strong>MUST</strong> be edited using the Elementor Page Builder and <strong>NOT</stong>
            the WordPress Block editor
        </p>
        
        <h3>Theme Colors</h3>
        <p>
            The Theme has been set up to use the same color for the top header bar, titles and clickable links.
            Since the color changes from Round Up year to year it needs to be customized each year.
        </p>
        <ul class="wantdisc">
            <li>Appearance</li>
            <li>Customize</li>
            <li>Additional CSS</li>
        </ul>
        <p>Change the following:</p>
        <p>--nsru-primary-clr: HEX-COLOR-CODE-FOR-PRIMARY;</p>
	    <p>--nsru-accent-clr: HEX-COLOR-CODE-FOR-ACCENT;</p>

        <h4>Menus</h4>
        <p>On the Dashboard Menu do the following:</p>
        <ul class="wantdisc">
            <li>Appearance</li>
            <li>Customize</li>
            <li>Menus</li>
            <li>Main Menu</li>
        </ul>
        <p>Add the following:</p>
        <pre>
            Create a Menu for all the pages on the main menu
            Menu Location - Click the Right Header Menu
        </pre>
        
        <h4>Headers</h4>
        <p>On the Dashboard Menu do the following:</p>
        <ul class="wantdisc">
            <li>Appearance</li>
            <li>Customize</li>
            <li>Header</li>
            <li>Styling</li>
        </ul>
        <p>Set the following</p>
        <pre>
            Header Arrangement - Logo Left
            Header Width - Full Width
            Sticky Header - Sticky
            Search - DON'T show search
            Header Styling - Background Color - Set to #ffffff
            Header Styling - Sticky Breakpoint (px) - Set to 1
        </pre>

        <h4>Footers</h4>
        <p>On the Dashboard Menu do the following:</p>
        <ul class="wantdisc">
            <li>Appearance</li>
            <li>Customize</li>
            <li>Footer</li>
            <li>Styling</li>
        </ul>
        <p>Set the following using &lsaquo;br /&rsaquo; for line breaks</p>
        <pre>
            Footer Width - Full Width
            Widget Areas - 1
            Copyright Text - Copyright © [nsru_get_year type="first"] - [nsru_get_year] | North Shore Round Up
            "We are going to know a new freedom and a new happiness". page 83 Big Book of AA
            This site is provided by the North Shore Round Up Committee as a service to the community.
            It is not endorsed by Alcoholics Anonymous World Services, does not represent Alcoholics Anonymous as a whole.
        </pre>
                
        <h3>Elementor Colors</h3>
        <p>Elementor needs to be customized to set the primary colors</p>
        <h4>Elementor</h4>
        <p>Edit any page with Elementor and do the following:</p>
        <ul class="wantdisc">
            <li>Click the Elementor menu (3 bars on left side of Elementor box</li>
            <li>Click Default Colors</li>
            <li>Click Primary</li>
        </ul>
        <p>Change to:</p>
        <pre>
            #b538a0
        </pre>
        <p>Where #b538a0 is the RGB value for the color desired</p>
        
    </div><!-- #Settings -->


    
    <div id="Settings" class="tabcontent">
    
        <p>NSRU Options Settings are used to quickly set/enable/disable attributes to do with the Round Up.</p>
        
        <hr />
        <h3>NSRU Colors</h3>
        <p>This section is used for the primary color for the site<br /></p>
        <ul class="wantdisc">
            <li>
                <strong>Main Color:</strong>The HEX value of the main color for the round up including the '#'.
            </li>
        </ul>
        <hr />
        <h3>Enable NSRU Options</h3>
        <p>This section is used for quick enabling and disabling of various sections for the Round Up.<br /></p>
        <ul class="wantdisc">
            <li>
                <strong>Enable PayPal Button:</strong> This enables or disables the PayPal purchase button.<br />
                When disabled it will show instructions on how to pick up the tickets at the Round Up.
            </li>
            <li>
                <strong>Enable Deluxe Hotel Rooms:</strong> This enables or disables the Deluxe Rooms.<br />
                When disabled it will show a message that the rooms are sold out.
            </li>
            <li>
                <strong>Enable Deluxe Harbour View Rooms:</strong> This enables or disables the Deluxe Harbour View Rooms.<br />
                When disabled it will show a message that the rooms are sold out.
            </li>
        </ul>

        <hr />
        <h3>Round Up Dates</h3>
        <p>This section is used to set all the Round Up Dates and Times<br /></p>
        <ul class="wantdisc">
            <li><strong>Round Up First Year</strong> This is the first year of the Round Up</li>
            <li>
                <strong>Start Date for Round Up</strong> This is the start date of the current Round Up which can
                be entered by selecting a date from the dropdown calendar or entering it directly as YYYY-MM-DD.
            </li>
            <li><strong>Start Time for Round Up</strong> This is the hour that the Round Up starts (24 hr clock)
            <li>
                <strong>End Date for Round Up</strong> This is the end date of the current Round Up which can
                be entered by selecting a date from the dropdown calendar or entering it directly as YYYY-MM-DD.
            </li>
            <li><strong>End Time for Round Up</strong> This is the hour that the Round Up ends (24 hr clock)
        </ul>

        <hr />
        <h3>Round Up Prices</h3>
        <p>This section is used to enter the ticket price, PayPal surcharge and PayPal Purchase Code.</p>
        <ul class="wantdisc">
            <li><strong>Ticket price for Round Up ($)</strong> Actual ticket price in dollars</li>
            <li><strong>PayPal Surcharge ($)</strong> PayPal surcharge for buying tickets on-line</li>
            <li>
                <strong>PayPal Purchase Code</strong> PayPal purchase code found in the hosted_button_id value
                when the PayPal Buy Now button is generated.  This code will re-create the PayPal Buy Now button.
            </li>
        </ul>

        <hr />
        <h3>Round Up Discounts</h3>
        <p>
            This section is used to enter the discounted ticket price information.<br />
            If there are no discounted tickets set the End Date for discounted tickets to a date earlier
            the current date.
        </p>
        <ul class="wantdisc">
            <li>
                <strong>End Date for discounted tickets</strong> This is the final date for discounted ticket prices which can
                be entered by selecting a date from the dropdown calendar or entering it directly as YYYY-MM-DD.
            </li>
            <li><strong>Discounted ticket price for Round Up ($)</strong> Discounted ticket price in dollars</li>
            <li><strong>Discount Ticket PayPal Surcharge ($)</strong> PayPal surcharge for buying tickets on-line</li>
            <li>
                <strong>PayPal Discount Purchase Code</strong> PayPal purchase code found in the hosted_button_id value
                when the PayPal Buy Now button is generated.  This code will re-create the PayPal Buy Now button.
            </li>
        </ul>

        <hr />
        <h3>Round Up Location</h3>
        <p>This section is used to enter all the information about the Round Up Location.</p>
        <ul class="wantdisc">
            <li><strong>Round Up Location Name</strong> The name of the venue</li>
            <li><strong>Round Up Location Address</strong> The address of the venue</li>
            <li><strong>Round Up Location Website</strong> The website of the venue</li>
        </ul>

        <hr />
        <h3>Round Up Hotel</h3>
        <p>This section is used to enter all the information about the Round Up Hotel.</p>
        <ul class="wantdisc">
            <li><strong>Hotel Name</strong> The name of the hotel</li>
            <li><strong>Hotel Address</strong> The address of the hotel</li>
            <li><strong>Hotel Website</strong> The website of the hotel</li>
            <li><strong>Hotel Local Phone Number</strong> The local phone number for room registration</li>
            <li><strong>Hotel Toll Free (Canadian) Number</strong> The Canadian Toll Free Number for room registration</li>
            <li><strong>Hotel Toll Free (USA) Number</strong> The US Toll Free number for room registration</li>
            <li><strong>Hotel Fax Number</strong> The hotel fax machine</li>
            <li><strong>Hotel Email</strong> The email for room registration</li>
        </ul>

        <hr />
        <h3>Round Up Special Room Rates</h3>
        <p>
            The hotel has two types of rooms, a deluxe room and a deluxe room with a harbour view.
            They can be enabled or disabled by checking or unchecking the check boxes at the top of the
            North Shore Round Up Settings page.
        </p>
        <p>If the rooms are enabled there <strong>MUST</strong> be a price associated with the room.</p>
        <ul class="wantdisc">
            <li><strong>Hotel Booking Code</strong> Telephone special room rate booking code</li>
            <li><strong>Hotel Booking Website</strong> Website for special room rate booking on-line</li>
            <li><strong>Hotel Special Price ($)</strong> Special room rate for Deluxe Room</li>
            <li><strong>Hotel Harbour View Special Price ($)</strong> Special room rate for Deluxe Room with Harbour View</li>
        </ul>

        <hr />
        <h3>Tracking Codes</h3>
        <p>This section is used to enter any of the tracking codes that can be used for website analytics.</p>
        <ul class="wantdisc">
            <li><strong>Google Analytics Code: </strong> Google Analytics Tracking Code</li>
        </ul>

    </div><!--- #Settings -->

    
    <div id="Shortcodes" class="tabcontent">
        <h2>North Shore Round Up Shortcodes</h2>

        <p>Shortcodes are used by WordPress to insert custom HTML code into a web page.</p>

        <p>
            The North Shore Round Up Options Plugin makes extensive use of shortcodes to provide information
            entered on the Setting Page of the Plugin to provide the HTML code.
        </p>

        <p>WordPress shortcode format is the following:</p>

        <p>[SHORTCODE_NAME ATTR_1 = "SOMETHING" ... ATTR_N = "SOMETHING ELSE"]</p>
        <ul class="wantdisc">
            <li>SHORTCODE_NAME - The name of the shortcode</li>
            <li>ATTR_? - One or more attributes passed into the shortcode</li>
        </ul>
        or
        <p>[SHORTCODE_NAME ATTR_1 = "SOMETHING" ... ATTR_N = "SOMETHING ELSE"]CONTENT[/SHORTCODE_NAME]</p>
        <ul class="wantdisc">
            <li>SHORTCODE_NAME - The name of the shortcode</li>
            <li>ATTR_? - One or more attributes passed into the shortcode</li>
            <li>CONTENT - Content that is passed to the shortcode</li>
        </ul>
        <hr />
        <p>The following shortcodes are available when using the plugin</p>

        <hr />
        <p><strong>nsru_committee</strong> - Formatted table of the current Round Up committee</p>
        <p>They will contain the Position, Name and Home Group or POSITION AVAILABLE if there isn't anyone in the position</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_committee]</p>
        
        <hr />
        <p><strong>nsru_get_annual</strong> - Number of the current Round Up with a suffix</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_annual] - Will return '48th Annual'</p>
        
        <hr />
        <p><strong>nsru_get_days_to</strong> - Number of days to the Round Up</p>
        <p>Will return the following:</p>
        <ul class="wantdisc">
            <li>starts in X days</li>
            <li>starts TODAY!</li>
            <li>is on NOW!</li>
            <li>is sadly over.<br />Thank you for making the Round Up a wonderful success.<br />We hope to see you at next years Round Up which starts in XXX days.</li>
        </ul>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_days_to] - Will return 'is on NOW!'</p>
        
        <hr />
        <p><strong>nsru_get_hotel_booking_code</strong> - Return the hotel telephone booking code or 
            that booking links are closed if there are no more deluxe or harbour view rooms</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_hotel_booking_code]</p>

        <hr />
        <p><strong>nsru_get_hotel_booking_link</strong> - Return the hotel on-line booking link or 
            that booking links are closed if there are no more deluxe or harbour view rooms</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_hotel_booking_link]</p>
        
        <hr />
        <p><strong>nsru_get_hotel_information</strong> - Returns various hotel information.</p>
        <p>Attributes:</p>
        <ul class="wantdisc">
            <li>type - Type of information
                <ul class="wantdisc">
                    <li><strong>name</strong> - Default: The name of the hotel</li>
                    <li><strong>local</strong> - Local phone number (will be clickable based on the link value)</li>
                    <li><strong>tollfree_can</strong> - Canadian Toll Free phone number (will be clickable based on the link value)</li>
                    <li><strong>tollfree_us</strong> - USA Toll Free phone number (will be clickable based on the link value)</li>
                    <li><strong>fax</strong> - Fax number (will be clickable based on the link value)</li>
                    <li><strong>email</strong> - Email Address (will be clickable based on the link value)</li>
                    <li><strong>website</strong> - Website link (will be clickable based on the link value)</li>
                    <li><strong>address</strong> - Address of the hotel</li>
                </ul>
            </li>
            <li>link - If a clickable link is desired
                <ul class="wantdisc">
                    <li><strong>true</strong> - Default: A link will be generated</li>
                    <li><strong>false</strong> - A link won't be generated</li>
                </ul>
            </li>
        </ul>
        <p>Content: - Will be pre-pended to the information</p>
        <p>eg: [nsru_get_hotel_information type="name"]Name of the hotel is: [/nsru_get_hotel_price] - Will return 'Name of the hotel is: Pan Pacific Vancouver'</p>

        <hr />
        <p><strong>nsru_get_hotel_price</strong> - Returns the price of a hotel room</p>
        <p>If the hotel room is disabled it will show that the rooms are sold out</p>
        <p>Attributes:</p>
        <ul class="wantdisc">
            <li>type - Type of room
                <ul class="wantdisc">
                    <li><strong>normal</strong> - Will return Deluxe Room for the special rate of $XXX</li>
                    <li><strong>harbour</strong> - Will return Deluxe Room with Harbour View for the special rate of $YYY</li>
                </ul>
            </li>
        </ul>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_hotel_price type="normal"] - Will return 'Deluxe Rooms are fully sold out'</p>

        <hr />
        <p><strong>nsru_get_how_paypal_works</strong> - Returns the How PayPal Works Link or nothing
            if on-line ticket sales are over</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_how_paypal_works]</p>

        <hr />
        <p><strong>nsru_get_round_up_dates</strong> - Days of the Round Up</p>
        <p>Will return the following:</p>
        <ul class="wantdisc">
            <li>StartDay - StopDay Month, Year (start and stop in same month)</li>
            <li>StartDay MonthStart - StopDay MonthStop, Year (start and stop in different months, but same year)</li>
            <li>StartDay MonthStart YearStart - StopDay MonthStop, YearStop (start and stop in different years)</li>
        </ul>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_round_up_dates] - Will return '19 - 21 April, 2019'</p>
        
        <hr />
        <p><strong>nsru_get_paypal</strong> - PayPal Buy Now Button</p>
        <p>The button will be formatted as a normal PayPal Buy Now button with either the discounted or normal ticket price</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_paypal]</p>
        
        <hr />
        <p><strong>nsru_get_price</strong> - Ticket price</p>
        <p>The price will be based on the date and will return if there is a discount or not</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_price] - Will return '45'</p>
        
        <hr />
        <p><strong>nsru_get_speakers</strong> - Formatted table of main speakers in the Round Up</p>
        <p>The speakers contain the name, city, province and organization they belong to</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_speakers]</p>
        
        <hr />
        <p><strong>nsru_get_surcharge</strong> - PayPal surcharge added to tickets</p>
        <p>The surcharge will be based on the current ticket price discounted or normal for the current date</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_surcharge] - Will return '1.65'</p>
        
        <hr />
        <p><strong>nsru_get_year</strong> - Returns the year of the Round Up which can be the first or
            the current year of the Round Up as a 4 digit number</p>
        <p>Attributes:</p>
        <ul class="wantdisc">
            <li>type - Type of year returned
                <ul class="wantdisc">
                    <li><strong>first</strong> - First year of the Round Up</li>
                    <li><strong>current</strong> - Current year of the Round Up</li>
                </ul>
            </li>
        </ul>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_get_year type="first"] - Will return '1972'</p>

        <hr />
        <p><strong>nsru_get_location_information</strong> - Returns various Round Up location information.</p>
        <p>Attributes:</p>
        <ul class="wantdisc">
            <li>type - Type of information
                <ul class="wantdisc">
                    <li><strong>name</strong> - Default: The name of the location</li>
                    <li><strong>website</strong> - Website link (will be clickable based on the link value)</li>
                    <li><strong>address</strong> - Address of the location</li>
                </ul>
            </li>
            <li>link - If a clickable link is desired
                <ul class="wantdisc">
                    <li><strong>true</strong> - Default: A link will be generated</li>
                    <li><strong>false</strong> - A link won't be generated</li>
                </ul>
            </li>
        </ul>
        <p>Content: - Will be pre-pended to the information</p>
        <p>eg: [nsru_get_location_information type="name"]Name of the Round Up venue is: [/nsru_get_location_information] - Will return 'Name of the Round Up venue is: Convention Center East'</p>

        <hr />
        <p><strong>nsru_meetings</strong> - Formatted table of the meetings for each day at the Round Up</p>
        <p>They will contain the time of the meeting, name, topic (optional), hosted/chaired by (optional), speakers (optional) and room</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_meetings]</p>
        
        <hr />
        <p><strong>nsru_past_chairs</strong> - Formatted table of past chairs</p>
        <p>They will contain the number of the Round Up, Year and Name</p>
        <p>Attributes: - NOT USED</p>
        <p>Content: - NOT USED</p>
        <p>eg: [nsru_past_chairs]</p>
       
    </div><!-- #Shortcodes -->
    
    
    <div id="Types" class="tabcontent">
        <h2>North Shore Round Up Custom Post Types</h2>

        <p>
            Custom post types are WordPress types that are used in the North Shore Round Up Options Plugin.
            They are created/modified like any other WordPress Post
        </p>
        
        <hr />
        <h3>Committee Members</h3>
        <p>
            These are a list of the committee members that are on the Round Up.<br />
            They will appear in the order they are displayed on the page.  The order can be adjusted by
            dragging the row into a new position.<br />
            If the position is open leave the name and the group blank and the plugin will fill in the
            appropriate information.
        </p>

        <hr />
        <h3>Past Chairs</h3>
        <p>
            These are a list of the Round Up Past Chairs.<br />
            They will appear in the order they are displayed on the page.  The order can be adjusted by
            dragging the row into a new position.
        </p>

        <hr />
        <h3>Meetings</h3>
        <p>
            These are a list of the Meetings at the Round Up.<br />
            The order that they will appear in is based on the date, time and then the room that they are held in.<br />
        </p>

        <hr />
        <h3>Speaker</h3>
        <p>
            These are a list of the Main Speakers at the Round Up.<br />
            They will appear in the order they are displayed on the page.  The order can be adjusted by
            dragging the row into a new position.
        </p>

        <hr />
        <h3>Rooms</h3>
        <p>These are a list of the Rooms used at the Round Up.</p>
        
    </div><!-- #Types -->
    
    <div id="Pages" class="tabcontent">
        <p><strong>ALL</strong> pages <strong>MUST</strong> be edited using Elementor</p>

        <hr />
        <h3>Home</h3>
        <p>The main page for the Round Up which is composed of 4 sections</p>
        <ol>
            <li>Section - Containing a column with a background image and heading that displays the Round Up information</li>
            <li>Section - Containing a column with text giving how many days to the Round Up</li>
            <li>Section - Containing a column with a Title for the Speakers and a shortcode to display the speakers</li>
            <li>Section - Containing a column with an Image of this years Round Up poster</li>
        </ol>

        <hr />
        <h3>Tickets</h3>
        <p>The tickets page for the Round Up which is composed of 5 sections</p>
        <ol>
            <li>Section - Containing a column with Heading</li>
            <li>Section - Containing a column with text giving the price of a ticket</li>
            <li>Section - Containing a column with text about where tickets can be bought</li>
            <li>Section - Containing a column with text about where to pick up on-line tickets</li>
            <li>Section - Containing two columns with shortcodes to display paypal and how paypal works</li>
        </ol>

        <hr />
        <h3>Schedule</h3>
        <p>The schedule page displays all the Round Up Meetings and is composed of 2 sections</p>
        <ol>
            <li>Section - Containing a column with Heading</li>
            <li>Section - Containing a columns with a shortcodes to display the list of meeting</li>
        </ol>

        <hr />
        <h3>Hotel</h3>
        <p>The hotel page displays all the Hotel information and is composed of 2 sections</p>
        <ol>
            <li>Section - Containing a column with Heading</li>
            <li>Section - Containing a columns with text and shortcodes to display the hotel information</li>
        </ol>

        <hr />
        <h3>FAQ</h3>
        <p>The FAQ page is composed of 14 sections that contain all the information in the FAQ</p>

        <hr />
        <h3>- Links</h3>
        <p>The links page displays all the useful links and is composed of 2 sections</p>
        <ol>
            <li>Section - Containing a column with Heading</li>
            <li>Section - Containing a columns with text and shortcodes to display the useful links</li>
        </ol>

        <hr />
        <h3>- Committee</h3>
        <p>The committee page displays current committee members and is composed of 2 sections</p>
        <ol>
            <li>Section - Containing a column with Heading</li>
            <li>Section - Containing a columns with a shortcodes to display the current committee</li>
        </ol>

        <hr />
        <h3>- History</h3>
        <p>The history page displays the history of the Round Up and the past chairs of the Round Up and is composed of 8 sections</p>
        <h3>- Past Posters</h3>
        <p>The past posters page displays the past posters of the Round Up and is composed of 2 sections</p>
        <ol>
            <li>Section - Containing a column with Heading</li>
            <li>Section - Containing a columns with an image gallery to display the old posters</li>
        </ol>

        <hr />
        <h3>Contact</h3>
        <p>This page contains the contact page</p>
        <ol>
            <li>Section - Containing a column with Heading</li>
            <li>Section - Containing 2 columns with a Contact form and a MailChimp subscription form</li>
        </ol>

        <hr />
        <h3>Privacy Policy</h3>
        <p>This page contains the Privacy Policy</p>
    </div><!-- #Pages -->
    
    
</div>

<script>
    document.getElementById("OverviewButton").click();
    
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
