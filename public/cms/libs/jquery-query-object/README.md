jquery-plugin-query-object
==========================

Query String Modification and Creation for jQuery

This extension creates a singleton query string object for quick and readable query 
string modification and creation. This plugin provides a simple way of taking a page's 
query string and creating a modified version of this with little code.  

Disclaimer  
-------------------------

There are many URI manipulation libraries for JS and before use jquery-query-object you should look at least to https://github.com/medialize/URI.js because it much more feature rich, tested and better documented. I support  jquery-query-object because i need it for my existent projects and i want to maintain reference to it on plugins.jquery.com - no other reasons to use it but not URI.js

Example
-------------------------

```
var url = location.search;
> "?action=view&section=info&id=123&debug&testy[]=true&testy[]=false&testy[]"
var section = $.query.get('section');
> "info"
var id = $.query.get('id');
> 123
var debug = $.query.get('debug');
> true
var arr = $.query.get('testy');
> ["true", "false", true]
var arrayElement = $.query.get('testy[1]');
> "false"
var newUrl = $.query.set("section", 5).set("action", "do").toString();
> "?action=do&section=5&id=123"
var newQuery = "" + $.query.set('type', 'string');
> "?action=view&section=info&id=123&type=string"
var oldQuery = $.query.toString();
> "?action=view&section=info&id=123"
var oldQuery2 = $.query;
> ?action=view&section=info&id=123
var newerQuery = $.query.SET('type', 'string');
> ?action=view&section=info&id=123&type=string
var notOldQuery = $.query.toString();
> "?action=view&section=info&id=123&type=string"
var oldQueryAgain = $.query.REMOVE("type");
> ?action=view&section=info&id=123
var removeElementByValue = $.query.REMOVE('section', 'info');
> ?action=view&id=123
var newerQuery2 = $.query.set('testy[]', 'true').set('testy[]', 'false').set('testy[]', 'true');
> ?action=view&id=123&testy[0]=true&testy[1]=false&testy[2]=true
var removeElementByValue1 = $.query.REMOVE('testy', 'false');
> ?action=view&id=123&testy[0]=true&testy[1]=true
var emptyQuery = $.query.empty();
> ""
var stillTheSame = $.query.copy();
> ?action=view&section=info&id=123
In case you dynamically change document.location via history API
var parsedQuery = $.query.parseNew("?foo=bar", "bar=foo");
> ?foo=bar&bar=foo
In case you are using History.js
var parsedQuery = $.query.parseNew(location.search, location.hash.split("?").length > 1 ? location.hash.split("?")[1] : "");
```

Features
-------------------------

 * **Chainability**  
    Like much of jQuery, this object supports chaining set methods to add new key 
    value pairs to the object. In addition, this chain does not modify the original 
    object, but returns a copy which can be modified without changing the original object. 
    You can use the method the 'loud' alternate methods to perform destructive 
    modifications on the original object.

 * **Direct Object 'get' Accessor**  
   The query string object returned contains the keys of the query string through a method named 'get'
   ```
   $.query.get(keypath)
   ```
 * **Easy String Creation**  
   All modern browsers convert JavaScript objects to their string representation through 
   their 'toString' method. So because this object creates a toString method for itself, 
   when evaluated in a string context, this object returns a valid query string. If the 
   query string object has no keys set, it returns an empty string. If it has keys set, 
   it automatically prefixes the output with a question mark so you don't need to worry 
   about appending one yourself. It's there if you need it and gone if you don't. 
   It also supports arrays and associative arrays inside the query string. Both regular 
   and associative arrays use "base[key1]=value1&base[key2]=value2" syntax in the query string.
   Originally arrays could forgo the square bracket and were simply printed out in their insertion 
   order but with the new deep object support in version 2.0 this had to be removed for the sake of 
   unambiguous keys.

 * **Custom url loading**  
   You can create a new query object based on a provided url through the load method

 * **Query String Parsing**  
   The original url parsing code was created by Jörn Zaefferer and modified by me.   
   The new parsing features added are:  
   1. **Parsing Integers**  
      The original code parsed floats out of strings but left integers as is. 
      In my release, '123' will be converted to the number 123.  
   2. **Boolean Values**  
      Query strings can often contain query parameters with no value set. This implies a simple boolean:  
      ```
      index.php?debug
      ```  
      implies a query string variable 'debug' set to true
   3. **Improved number parsing**
      Parsing features introduced in version 1.2 include better support for number formats and differentiating between number-looking strings and numbers.
   4. **Array syntax parsing**
      Array shaped query string keys will create an array in the internal jQuery.query structure and using square brackets without an index will generate an array dynamically.
   5. **Ampersand or semi-colon delimiters**
      Because it has been requested of me, the parser now supports both semi-colons and ampersands as delimiting marks between key value pairs.
   6. **Hash parameter parsing**
      Because it has been requested of me, the parser now supports both query string parsing and parsing of query string like strings in the url hash.

 
Customization
-------------------------

There are now some customizations which can be done, mostly dealing with parsing and with toString generation. The options are set by creating jQuery.query as an object of settings before including the jQuery.query source code on your page as seen below:

```
<script type="text/javascript">
var jQuery.query = { numbers: false, hash: true };
</script>
<script type="text/javascript" src="jquery.query.js"></script>
```

When initializing, the query object will check the currently existing jQuery.query for any settings it can use. The settings are:

   1. **separator**  
      The default value for this setting is '&' as that is the standard for parameter division. However, when working in xml, some prefer to use a semi-colon to separate parameters to avoid overuse of &amp;. The parser has been updated to read semi-colons as delimiters but to output generated query strings with a semi-colon you need to set this setting to ';'

   2. **spaces**  
      The default value for this is true as most people prefer plus signs in query strings to be converted to spaces. It's standard practice to use plus signs to represent spaces in query strings to avoid the dreaded %20 so the parser has been updated and, by default, converts plus signs to spaces. However, this feature can be disabled if you decide you need literal plus signs in your query strings.

   3. **suffix**  
      The default for this is true. If set to true, any arrays, when outputted to a query string will be suffixed with "[]" to show them as an array more clearly. Some may prefer to generate array strings without the square brackets and can set this value to false. I set this to true by default because I prefer one element arrays to be unambiguously arrays when generated in a query string.

   4. **hash**  
      The default for this is false. If set to true, the output of a query string will be prefixed by a hash '#' rather than a question mark. Given the interest in parsing hash parameters along with query string parameters, this seemed like a good setting to introduce.

   5. **prefix**  
      The default for this is true. If set to false, the output of a query string is not prefixed by a hash or question mark.

   6. **numbers**  
      The default for this is true. If set to false, numbers in query strings will NOT be parsed. This helps when left-hand zeros in a number are significant for some reason.

Limitations
-------------------------

* **Direct Object Access**  
    Direct object access has been removed from this plugin as it wasn't symmetric (ie it was only usable for getting, not setting) and for safety reasons.

* **Boolean false Values**  
    Because true values are parsed and represented as attributes without distinct values, false is represented as a lack of attribute. Because of this, attempting to set an attribute to 'false' will result in its removal from the object. This is a design decision which may be made a customizable setting in the future.

* **Key Complexity**  
    Version 2.0 and above now supports deep objects just as well as PHP's built in $_GET object.
