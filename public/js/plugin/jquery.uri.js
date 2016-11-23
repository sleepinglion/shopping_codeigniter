/* ===========================================================================
 *
 * JQuery URI Utility version 1.0
 * Parses URIs and provides easy access to information within them.
 *
 * Author: Mark Perkins
 * Author email: mark@allmarkedup.com
 * Docs, demos and info coming soon.
 *
 * ---------------------------------------------------------------------------
 *
 * CREDITS:
 *
 * Parser based on the Regex-based URI parser by Stephen Levithian.
 * For more information (including a detailed explaination of the differences
 * between the 'loose' and 'strict' pasing modes) visit http://blog.stevenlevithan.com/archives/parseuri
 *
 * ---------------------------------------------------------------------------
 *
 * LICENCE:
 *
 * Released under a MIT Licence. See licence.txt that should have been supplied with this file.
 *
 * ---------------------------------------------------------------------------
 * 
 * EXAMPLES OF USE:
 *
 * Get the domain name (host) from the current page URI
 * jQuery.uri.key("host")
 *
 * Get the query string value for 'item' for the current page
 * jQuery.uri.param("item") // null if it doesn't exist
 *
 * Get the second segment of the URI of the current page
 * jQuery.uri.segment(2) // null if it doesn't exist
 *
 * Get the protocol of a manually passed in URL
 * jQuery.uri.setUri("http://allmarkedup.com/").key("protocol") // returns 'http'
 *
 */


jQuery.uri = function()
{
	var segments = {};
	
	var queryParams = {};
	
	var parsed = {};
	
	/**
    * Options object. Only the URI and strictMode values can be changed via the setters below.
    */
  	var options = {
	
		uri : window.location, // default URI is the page in which the script is running
		
		strictMode: false, // 'loose' parsing by default
	
		key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"], // keys available to query 
		
		q: {
			name: "queryKey",
			parser: /(?:^|&)([^&=]*)=?([^&]*)/g
		},
		
		parser: {
			strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/, // more intuitive, fails on relative paths and deviates from specs
			loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ //less intuitive, more accurate to the specs
		}
		
	};
	
    /**
     * Deals with the parsing of the URI according to the regex above.
 	 * Written by Steven Levithan - see credits at top.
     */		
	var parseUri = function()
	{
		str = decodeURI(options.uri);
		
		m = options.parser[ options.strictMode ? "strict" : "loose" ].exec( str ),
		uri = {},
		i = 14;

		while ( i-- ) uri[ options.key[i] ] = m[i] || "";

		uri[ options.q.name ] = {};
		uri[ options.key[12] ].replace( options.q.parser, function ( $0, $1, $2 ) {
			if ($1) uri[options.q.name][$1] = $2;
		});

		return uri;
	}

    /**
     * Returns the value of the passed in key from the parsed URI.
  	 * 
	 * @param string key The key whose value is required
     */		
	var key = function( key )
	{
		if ( ! parsed.length ) setUp(); // if the URI has not been parsed yet then do this first...
		return ( parsed[key] === "" ) ? null : parsed[key];
	}
	
	/**
     * Returns the value of the required query string parameter.
  	 * 
	 * @param string item The parameter whose value is required
     */		
	var param = function( item )
	{
		if ( ! parsed.length ) setUp(); // if the URI has not been parsed yet then do this first...
		return ( queryParams[item] == null ) ? null : queryParams[item];
	}

    /**
     * 'Constructor' (not really!) function.
     *  Called whenever the URI changes to kick off re-parsing of the URI and splitting it up into segments. 
     */	
	var setUp = function()
	{
		parsed = parseUri();
	
		getSegments();
		parseQueryString();	
	};
	
    /**
     * Splits up the body of the URI into segments (i.e. sections delimited by '/')
     */
	var getSegments = function()
	{
		var p = parsed.path;
		segments = new Array(); // clear out segments array
		segments = parsed.path.length == 1 ? {} : ( p.charAt( p.length - 1 ) == "/" ? p.substring( 1, p.length - 1 ) : path = p.substring( 1 ) ).split("/");
	}
	
    /**
     * Parses the querystring (if there is one) and splits it into compnent key=value pairs, stored in the queryParams object
     */
	var parseQueryString = function()
	{
		querystring = parsed.query.split(/[;&]/); // matches '&' or ';' as the split character as per the specs
	  	for ( var i=0; i< querystring.length; i++ ) 
		{
			var keyVal = querystring[i].split("=");
			queryParams[decodeURI( keyVal[0] )] = decodeURI( keyVal[1] );
	  	}
	}
	
	return {
		
	    /**
	     * Sets the parsing mode - either strict or loose. Set to loose by default.
	     *
	     * @param string mode The mode to set the parser to. Anything apart from a value of 'strict' will set it to loose!
	     */
		setMode : function( mode )
		{
			strictMode = mode == "strict" ? true : false;
			return this;
		},
		
		/**
	     * Sets URI to parse if you don't want to to parse the current page's URI.
		 * Calling the function with no value for newUri resets it to the current page's URI.
	     *
	     * @param string newUri The URI to parse.
	     */		
		setUri : function( newUri )
		{
			options.uri = newUri === undefined ? window.location : newUri;
			setUp();
			return this;
		},		
		
		/**
	     * Returns the value of the specified URI segment. Segments are numbered from 1 to the number of segments.
		 * For example the URI http://test.com/about/company/ segment(1) would return 'about'.
		 *
		 * If no integer is passed into the function it returns the number of segments in the URI.
	     *
	     * @param int pos The position of the segment to return. Can be empty.
	     */	
		segment : function( pos )
		{
			if ( ! parsed.length ) setUp(); // if the URI has not been parsed yet then do this first...
			if ( pos === undefined ) return segments.length;
			return ( segments[pos] == "" || segments[pos] == undefined ) ? null : segments[pos];
		},
		
		parseUri : parseUri,
		
		key : key, // provides public access to private 'key' function - see above
		
		param : param // provides public access to private 'param' function - see above
		
	}
	
}();