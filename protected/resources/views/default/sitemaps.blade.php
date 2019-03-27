<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	
	<url>
         <loc>{{ URL::to('/') }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
   
   <url>
         <loc>{{ URL::to('jobs') }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
   
   <url>
         <loc>{{ URL::to('designers') }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
   
   <url>
         <loc>{{ URL::to('goods') }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
   
   <url>
         <loc>{{ URL::to('projects') }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
   
   <url>
         <loc>{{ URL::to('popular') }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
   
	@foreach( Pages::all() as $page )
	<url>
         <loc>{{ URL::to('/').'/'.$page->slug }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
 @endforeach
   
	@foreach( User::where('status','active')->get() as $user )
	<url>
         <loc>{{ URL::to('@').$user->username }}</loc>
         <lastmod>2015-03-10</lastmod>
         <priority>0.8</priority>
   </url>
   @endforeach
</urlset>