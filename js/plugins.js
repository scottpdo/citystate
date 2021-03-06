// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function f(){ log.history = log.history || []; log.history.push(arguments); if(this.console) { var args = arguments, newarr; args.callee = args.callee.caller; newarr = [].slice.call(args); if (typeof console.log === 'object') log.apply.call(console.log, console, newarr); else console.log.apply(console, newarr);}};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

// Parse the window slug
CS.parseSlug = function(segment) {
	// Split by the segment and hash and choose the last in the resulting array
	var slug = window.location.href.split(CS.BASE + '/' + segment + '/#/');
	slug = slug[slug.length - 1];

	// Remove any modifiers that might have snuck in from the segment
	var modifiers = ['?', '&', '.'];
	for (var i = 0; i < modifiers.length; i++) {
		if (slug.indexOf(modifiers[i]) > -1) {
			slug = slug.slice(0, slug.indexOf(modifiers[i]));
		}
	}
	return slug;
}

CS.commas = function(string) {

	string += '';
	x = string.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2; 
}

CS.slugify = function(string) {
	return string.replace(/[^a-zA-Z0-9-]/g, '-').toLowerCase().replace(/--+/g, '-')
}