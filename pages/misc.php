<h1>SVN over SSH with TortoiseSVN</h1>
<p>Check out the repository with the following URI:
<pre>svn+ssh://username@session/full/path/to/svn</pre>
<p>
    Make sure that <code>session</code> is the name of the saved session in PuTTY. <strong>The 
    actual hostname doesn't occur anywhere in the URL.</strong> Also note that you need to put in 
    the <strong>full</strong> path to the repository, <strong>not relative</strong> to whatever is 
    configured server-side. As a final note, make sure the Network settings in TortoiseSVN are 
    correct. The SSH-client setting should point to 
    <code>\Program Files\TortoiseSVN\bin\TortoisePlink.exe</code>.

<h1>Useful snippets</h1>
<p><small>(for me)</small>
<pre>
	<code>
#if DEBUG
    #define dprintf(...) printf(__VA_ARGS__)
    #define dfprintf(...) fprintf(__VA_ARGS__)
#else
    #define dprintf(...)
    #define dfprintf(...)
#endif
	</code>
</pre>