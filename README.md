	INTRODUCTION
The main aim of this project is to build a multilingual faceted search system having a GUI that allows users to search and browse multilingual data based on various criteria like multi-lingual search, hashtag faceted search, data analytics, etc. We are using twitter and Facebook data as our multilingual social media corpus. Some of the topics reflected in the dataset are Syrian refugee crisis, recent Paris attacks, California shooting incident and some other prominent topics related to politics, sports, technology and recent news. Our corpus is not restricted to English language we have also included German, French and Russian posts. We have implemented Content Tagging, Faceted Search, Cross Lingual Retrieval Analysis, Ranking Tweets and Graphical Analysis as some of our functionalities.

	DATA COLLECTION AND SOURCE
Sources: Facebook, Twitter and News websites
Languages chosen: English, German, Russian and French
Topics: Paris Attacks, Syrian Refugee crisis, 2016 US Presidential elections, California shooting incident, Technology, Sports, Politics, Recent news
Around 12000 posts were collected on all the above mentioned data and languages.

	INDEX
We have handled removal of duplicate tweets while indexing, that is retweets will not be indexed. We have handled this by initially storing all the retrieved data in the text field which is handled in the Solr schema. By setting a unique key on the text field and not tokenizing it we have managed to remove duplication of tweets. On querying this data we get unique data in Json format (as wt =json) . Following is the query used to display unique data,	                 http://agra1992.koding.io:8983/solr/newcore/select?q=*%3A*&rows=10000&wt=json&indent=true	       This newly obtained data can then be saved in another file which can be re-indexed. The newly indexed data will not have any repeated tweets.We have chosen VSM (Vector space model) IR model for query processing.

	FRONT-END DESCRIPTION
The front-end of this project has been developed using various technologies. We used HTML5/CSS3 to build the overall feel of the website. jQuery was used to add more dynamic functionality to our website like check box persistence for faceted search and other such functionalities. Our site is built using the Twitter Bootstrap framework which allowed us to lay the contents of the main search page in three distinctive columns. The first columns stores the facet options and the other two columns displays the required information. The overall look and feel of the site has been kept rather up-beat and it uses a card-system to display data as in many popular social media sites like Facebook, Tumblr, etc.

	BACK-END DESCRIPTION
Behind our gorgeous front-end, we have added the main crux of functionality of our website using PHP and Solr. We are using PHP to dynamically interact with Solr to retrieve query results from the server. All query results are returned as JSON and they are appropriately parsed and formatted into the front-end. We are also interacting with several APIs using PHP to dynamically retrieve more information about tweets and format them onto the Front End. Overall, we have been able to achieve a great sync between the look and feel of the website and the actual back-end functionality.
