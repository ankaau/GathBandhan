/**
 * @package Module Smart Countdown for Joomla! 2.5 - 3.0
 * @version 2.3.1: smartcdpro.js
 * @author Alex Polonski
 * @copyright (C) 2012-2014 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html height
**/
// Counters container
// Use existing object or create a new one
var scdpCounters = scdpCounters || {
	counters : [],
	fire_all_counters : function() {
		this.counters.each(function(counter) {
			counter.go();
		});
	},
	timer_id : 0,
	// Add a new countdown
	add : function(id, events_queue, options) {
		this.counters[id] = new ScdpCounter(id, events_queue, options);
		if(this.timer_id == 0) {
			this.timer_id = setInterval(function() {
				scdpCounters.fire_all_counters();
			}, 1000 );
		}
	},
	
	/* DOM observers support
	 * Events import plugins can require real time event text (or other counter's
	 * HTML elements) updates. Plugin's script will handle this logic according to
	 * its configuration and data, but we must provide a trigger for a forced
	 * update for the cases when countdown mode changes and/or next event is pulled
	 * from the events queue.
	 * 
	 * Plugins can register observers by passing the key (normally - the plugin's
	 * name, and plugin's js object to addObserverObj() method below.
	 * 
	 * *** All plugin's js objects must implement runObserver(ids_suffix) ***
	 */
	observers : {},
	// add a new observer by key. Adding the object with the same key will
	// have no effect and is safe.
	addObserverObj : function(key, obj) {
		this.observers[key] = obj;
	},
	// trigger all registered observers
	triggerObservers : function(ids_suffix) {
		for(key in this.observers) {
			this.observers[key].runObserver(ids_suffix);
		}
	}
};

ScdpDigit = new Class({
	Implements : [Options],
	options : {
		container : null,
		digit : 0,
		mode : null
	},
	
	value : null,
	fxEls : null,
	elsData : [],
	
	curGroup : 0,
	instantDisplay : false,
	
	fxGroups : [],
	error : '',
	
	fxRunning : false,
	
	initialize: function(options) {
		
		if(this.value === null) {
			this.setOptions(options);
		}
		
		this.setElements(options.mode);
	},
	
	setElements : function(mode) {
		this.options.mode = mode;
		this.fxRunning = false;
		
		var wrapper = document.id('sc' + this.options.container.getIdsSuffix() + '_wrapper_' + this.options.digit);
		if(wrapper == null) {
			this.options.error = 'Element not found in DOM'; //$$$ to do - add error check...
			return;
		}
		
		var config = JSON.decode(wrapper.getChildren('input[type=hidden]').pop().get('value'));

		var data = config[this.options.mode];
		
		// Empty all elements inside wrapper
		// It is needed for mode change case: new mode configuration
		// may be quite different require a new set of elements
		wrapper.empty();
		var els = [];
		this.fxGroups.empty();
		this.elsData.empty();
		
		// Start iteration for all elements of all groups. The resulting
		// els array will include all unique fx-elements
		// fxGroups array will store ALL fx-elements with their index in els and tweens,
		// prepared to be used in Fx.Elements.start function
		
		var uniqueIndex = -1;
		data.each(function(group) {
			// prime defaults if not set
			var transition = group.transition ? group.transition : '';
			var unit = (typeof(group.unit) !== "undefined" && group.unit !== null) ? group.unit : '%';
			var groupData = {duration: group.duration, unit: unit, transition: transition, elements : {}};
			
			group.elements.each(function(elData) {
				var el;
				
				if(elData.content_type != 'static-bg') {
					// fx-element: it must be added to els (as html element) and 
					// to this.elsData (as data object with sc properties) if it is unique
					// and added to this.fxGroups always (if has tweens)
					
					// search for existing element in elsData
					var index = -1;
					
					this.elsData.each(function(el, i) {
						if(el.value_type == elData.value_type &&
							el.tag == elData.tag &&
							el.content_type == elData.content_type &&
							el.filename_base == elData.filename_base &&
							el.filename_ext == elData.filename_ext) {
							
							index = i;
						}
					});
					
					if(index < 0) {
						
						// unique
						index = ++uniqueIndex;
						
						// create and adopt element
						el = new Element(elData.tag, {'class': 'sc-fx-element', 'style': elData.styles});
						
						wrapper.adopt(el);
						
						this.elsData[index] = elData;
						els.push(el);
						
						var contentKey = '_' + elData.filename_base;
						
						if(typeof(this.options.container.getContentByKey(contentKey)) != 'object') {
							this.options.container.setContent(contentKey, this.createContent(elData));
						}
					}
					
					// add all elements with tweens to groups, even if the
					// element is not unique
					if(Object.getLength(elData.tweens)) {
						groupData.elements[index] = elData.tweens;
					}
				} else {
					// static-element: we adopt it to the wrapper but do not include in
					// this.elsData, els and this.fxGroups. It needs not to be refreshed on
					// counter tick events
					
					el = new Element(elData.tag, {'class': 'sc-static-element', 'style': elData.styles});
					if(elData.tag == 'img') {
						el.set('src', this.options.container.getImageFolder() + elData.filename_base + elData.filename_ext);
					}
					wrapper.adopt(el);
				}
				
			}, this);
			
			this.fxGroups.push(groupData);
			
		}, this);
		
		// reinject configuration data
		wrapper.adopt(new Element('input', {type: 'hidden', value: JSON.encode(config)}));
		
		this.fxEls = new Fx.Elements(els, {
			unit : '%',
			link : 'ignore',
			//fps : '24',
			onComplete : function() {
				this.fxRunning = false;
				
				// Explicitely implement chain link behavior
				// we cannot use a standard chain link here to
				// support different options for successive calls
				// to start - if there are more than 2:
				// the third call happens before the second animation
				// begins and will overwrite its options (duration e.g.)
				
				
				if(++this.curGroup < this.fxGroups.length) {
					var toRun = this.fxGroups[this.curGroup];
					this.fxEls.options.unit = toRun.unit;
					this.fxEls.options.transition = toRun.transition;
					this.fxEls.options.duration = this.instantDisplay ? 0 : toRun.duration;
					
					this.fxEls.start(toRun.elements);
				} else {
					
					// Mark the chain complete
					this.fxRunning = false;
				}
				
			}.bind(this)
		});
	},
	
	createContent : function(elData) {
		var content = [];
		
		switch(elData.content_type) {
		
		case 'img':
			for(var i = 0; i < 10; i++) {
				// $$$ exclude style...
				content[i] = {style: 'margin:0;', 'alt' : i, src : this.options.container.getImageFolder() + elData.filename_base + i + elData.filename_ext};
				//content[i] = {'alt' : i, src : this.options.container.getImageFolder() + elData.filename_base + i + elData.filename_ext};
			}
			break;
			
		case 'uni-img':
			for(var i = 0; i < 10; i++) {
				// same content for all values
				content[i] = {'alt' : i, src : this.options.container.getImageFolder() + elData.filename_base + elData.filename_ext};
			}
			break;
			
		default: // txt
			for(var i = 0; i < 10; i++) {
				content[i] = {html: i};
			}
		}
		
		return content;
	},
	
	getValue : function() {
		return this.value;
	},
	
	getError : function() {
		return this.options.error;
	},
	
	start : function(vStack, instantDisplay) {
		
		if(this.fxRunning) {
			// there is an animation in progress - do not start a new one, just wait while
			// all programmed animations are finished
			
			return;
		}
		
		// set instantDisplay if set in arguments and for initialization
		this.instantDisplay = (typeof(this.value) === "undefined" || this.value === null || instantDisplay);
		
		// set initial values and styles - prepare for fx animation
		this.fxEls.elements.each(function(el, i) {
			var valueIndex = vStack[this.elsData[i].value_type];
			var part = this.options.container.getContentByKey('_' + this.elsData[i].filename_base);
			
			var style = this.elsData[i].styles;
			
			// set all properties including style at once
			var props = Object.clone(part[valueIndex]);
			
			// if style is set in content (for background elements e.g.)
			// add it to element's preset styles
			props.style = props.style ? style + props.style : style;
			
			el.setProperties(props);
		}, this);
		
		if(this.fxGroups.length) {
			this.curGroup = 0;
			var toRun = this.fxGroups[0];
			this.fxEls.options.unit = toRun.unit;
			this.fxEls.options.transition = toRun.transition;
			this.fxEls.options.duration = this.instantDisplay ? 0 : toRun.duration;
			
			// Mark animation as started
			this.fxRunning = true;
			
			this.fxEls.start(toRun.elements);
		}
		
		// update digit value
		this.value = vStack['next'];
	}
});

ScdpDigits = new Class({
	Implements : [Options],
	
	options : {
		ids_suffix : '_0',
		mode : 'down',
		images_folder : '',
		min_days_width : 0
	},
	
	digits : [],
	contentTable : {},

	initialize : function(values, options) {
		
		var isNew = this.digits.length == 0;
		if(!isNew) { // soft init
			if(this.digits.length != values.length) {
				// deal with digits number change
				if(this.digits.length < values.length) {
					this.addDayDigit(values);
					this.start(values);
				} else {
					this.removeDayDigit();
					this.start(values);
				}
			}
			if(this.options.mode != options.mode) {
				// mode change
				this.setOptions(options);
				this.contentTable = {};
				this.digits.each(function(d) {
					d.setElements(this.options.mode);
				}, this);
				// add true as the last parameter - modeChange
				this.start(values, false, true);
			} else {
				// resume
				this.start(values, true);
			}
		} else { // full init
			this.setOptions(options);
			values.each(function(v, i) {
				// construct digits
				this.digits[i] = new ScdpDigit({digit: i, container: this, mode: this.options.mode});
			}, this);
			
			// initial display
			this.start(values, true);
			
			// adjust labels position if neeed
			this.adjustLabelsPos();
		}
		
		// Adjust units width for table layout (both full-init and days digit add/remove)
		if(this.options.table_layout) {
			var numberDivs = $$('#scdpro' + this.options.ids_suffix + ' .scdp-number');
			numberDivs.setStyle('min-width', '');
			var maxWidth = this.options.min_days_width * 1; // read setting of minimum reserved width
			numberDivs.each(function(d) {
				var dim = d.getDimensions();
				if(dim.width > maxWidth) {
					maxWidth = dim.width;
				}
			});
			numberDivs.setStyle('min-width', maxWidth);
		}
	},
	
	getIdsSuffix : function() {
		return this.options.ids_suffix;
	},
	
	getContentByKey : function(key) {
		return this.contentTable[key];
	},
	
	setContent : function(key, data) {
		this.contentTable[key] = data;
	},
	
	getImageFolder : function() {
		return this.options.images_folder;
	},
	
	start : function(values, instantDisplay, modeChange) {
		if(this.digits.length == values.length) {
			this.digits.each(function(d, i) {
				if(values[i] != d.getValue() || instantDisplay || modeChange) {
					var modeDown = this.options.mode == 'down';
					var newValue = +values[i];
					var curValue = d.getValue();
					
					if(typeof(curValue) === "undefined" || curValue === null) {
						// initialization case - we need to calculate curValue as one step from newValue
						curValue = getPrevValue(modeDown, newValue, i, values[5]);
					}
					
					// Calculate the rest of values:
					// post-next - 1 step from newValue
					// pre-prev - 1 step from valid curValue
					
					var post_next = getNextValue(modeDown, newValue, i, values[5]);
					var pre_prev = getPrevValue(modeDown, curValue, i, values[5]);
					
					var vStack = {'pre-prev': pre_prev, 'prev': curValue, 'next': newValue, 'post-next': post_next};
					
					d.start(vStack, instantDisplay || (modeChange && i != 0));
				}
			}, this);
		} else {
			this.initialize(values, {mode: this.options.mode});
		}
		
		function getPrevValue(modeDown, v, i, hoursHigh) {
			if(i == 0 || i == 2 || i >= 6) {
				if(modeDown) {
					if(++v > 9) v = 0;
				} else {
					if(--v < 0) v = 9;
				}
			} else if(i == 1 || i == 3) {
				if(modeDown) {
					if(++v > 5) v = 0;
				} else {
					if(--v < 0) v = 5;
				}
			} else if(i == 5) {
				if(modeDown) {
					if(++v > 2) v = 0;
				} else {
					if(--v < 0) v = 2;
				}
			} else if(i == 4) {
				var hours = ('' + hoursHigh + v) * 1;
				if(modeDown) {
					hours++;
					if(hours > 23) hours = '00';
				} else {
					hours--;
					if(hours < 0) hours = '23';
				}
				v = ('' + hours).pad(2, '0', 'left').charAt(1);
			}
			return v;
		}
		
		function getNextValue(modeDown, v, i, hoursHigh) {
			if(i == 0 || i == 2 || i >= 6) {
				if(!modeDown) {
					if(++v > 9) v = 0;
				} else {
					if(--v < 0) v = 9;
				}
			} else if(i == 1 || i == 3) {
				if(!modeDown) {
					if(++v > 5) v = 0;
				} else {
					if(--v < 0) v = 5;
				}
			} else if(i == 5) {
				if(!modeDown) {
					if(++v > 2) v = 0;
				} else {
					if(--v < 0) v = 2;
				}
			} else if(i == 4) {
				var hours = ('' + hoursHigh + v) * 1;
				if(!modeDown) {
					hours++;
					if(hours > 23) hours = '00';
				} else {
					hours--;
					if(hours < 0) hours = '23';
				}
				v = ('' + hours).pad(2, '0', 'left').charAt(1);
			}
			return v;
		}
	},
	
	addDayDigit : function(values) {
		var wrapper = document.id('sc' + this.getIdsSuffix() + '_wrapper_' + (this.digits.length - 1));
		var parent = wrapper.getParent();
		var newDigit = wrapper.clone();
		newDigit.set('id', 'sc' + this.getIdsSuffix() + '_wrapper_' + this.digits.length);
		
		// digits floating left, so the highest digit (the new one)
		// must go on top of container parent
		parent.grab(newDigit, 'top');
		this.digits.push(new ScdpDigit({ digit: this.digits.length, container: this, mode: this.options.mode }));
	},
	
	removeDayDigit : function() {
		var wrapper = document.id('sc' + this.getIdsSuffix() + '_wrapper_' + (this.digits.length - 1));
		wrapper.destroy();
		this.digits.pop();
	},
	
	adjustLabelsPos : function() {
		// adjust labels position if neeed (if vertical label position is set)
		if(this.options.labels_vert_pos) {
			var labelDivs = $$('#scdpro' + this.options.ids_suffix + ' .scdp-label');
			labelDivs.each(function(l) {
				// set label div height equal to sibling number
				var numberDiv = l.getSiblings('.scdp-number').pop();
				var height = numberDiv.getSize().y;
				l.setStyle('height', height);
				
				// get current label-wrapper height
				var wrapper = l.getChildren('.scdp-label-wrapper').pop();
				var labelHeight = wrapper.getSize().y;
				
				wrapper.setStyle('position', 'relative');
				
				var top;
				
				switch(this.options.labels_vert_pos) {
				case 'top':
					top = 0;
					break;
				case 'bottom':
					top = height - labelHeight;
					break;
				case 'superscript':
					top = labelHeight * -0.5;
					break;
				case 'subscript':
					top = height - labelHeight /5;
					break;
				default:
					top = height / 2 - labelHeight / 2;
				}
				
				wrapper.setStyle('top', top);
			}.bind(this));
		}
	}
});

ScdpCounter = new Class({
	Implements : [Options],
	
	options : {
		hide_zero_fields : false,
		display_seconds : true,
		compact_view : false,
		event_goto_link : '',
		images_folder : '',
		table_layout : true,
		labels_vert_pos : 'middle',
		blinking_separator : 0,
		min_days_width : 0,
		left_pad_days : 0,
		counter_clickable : 0,
		confirm_redirect : 1,
		strings : {
			// language-specific unit labels, will be overridden in options
			MOD_SMARTCDPRO_N_DAYS : 'Days',
			MOD_SMARTCDPRO_N_DAYS_1 : 'Day',
			MOD_SMARTCDPRO_N_HOURS : 'Hours',
			MOD_SMARTCDPRO_N_HOURS_1 : 'Hour',
			MOD_SMARTCDPRO_N_MINUTES : 'Minutes',
			MOD_SMARTCDPRO_N_MINUTES_1 : 'Minute',
			MOD_SMARTCDPRO_N_SECONDS : 'Seconds',
			MOD_SMARTCDPRO_N_SECONDS_1 : 'Second',
			MOD_SMARTCDPRO_REDIRECT_CONFIRM_HINT : 'Go to the event\'s page?'
		}
	},
	
	text_down : '',
	text_up : '',
	ids_suffix : '',
	awake_detect : 0,
	awake_correction : 0,
	is_text_dirty : true,
	mode : null,
	events_queue : [],
	diff : 0,
	up_limit : 0,
	delta : 0,
	
	digits : null,
	
	initialize : function(id, events_queue, options) {
		this.setOptions(options);
		this.events_queue = events_queue;
		
		// ids_suffix will be appended to each field id
		// so we can have various counters on the same page without ids conflict
		this.ids_suffix = '_' + id;
		this.container_field = document.id('scdpro' + this.ids_suffix);
		
		if(!this.mode) {
			// get first event in queue - only once, on full init
			var ev = this.nextEvent();
			if(ev) {
				this.text_down = ev.text_down;
				this.text_up = ev.text_up;
				this.diff = ev.diff_js;
				this.up_limit = ev.up_limit;
			} else {
				this.container_field.hide();
				return;
			}
		}
		
		// event text needs update flag
		this.is_text_dirty = true;
		
		// Find DOM elements
		
		// event text
		this.text_field = document.id('scdpro_text' + this.ids_suffix);
		
		// groups
		this.days_field = document.id('scdpro_days' + this.ids_suffix);
		this.hours_field = document.id('scdpro_hours' + this.ids_suffix);
		this.minutes_field = document.id('scdpro_minutes' + this.ids_suffix);
		this.seconds_field = document.id('scdpro_seconds' + this.ids_suffix);
		
		// labels (for JText updates)
		
		this.days_label_field = this.days_field.getElements('.scdp-label-wrapper').pop();
		this.hours_label_field = this.hours_field.getElements('.scdp-label-wrapper').pop();
		this.minutes_label_field = this.minutes_field.getElements('.scdp-label-wrapper').pop();
		this.seconds_label_field = this.seconds_field.getElements('.scdp-label-wrapper').pop();
		
		this.days_label_type = this.getLabelType('days');
		this.hours_label_type =  this.getLabelType('hours');
		this.minutes_label_type = this.getLabelType('minutes');
		this.seconds_label_type = this.getLabelType('seconds');
		
		var values = this.getValues(this.diff);
		
		// construct the counter digits
		this.digits = new ScdpDigits(values,
			{
				ids_suffix: this.ids_suffix,
				images_folder: this.options.images_folder,
				mode: this.mode,
				table_layout : this.options.table_layout,
				labels_vert_pos : this.options.labels_vert_pos,
				min_days_width : this.options.min_days_width
			}
		);
		
		this.awake_detect = new Date().getTime();
	},
	
	nextEvent : function() {
		if(this.events_queue.length) {
			var ev = this.events_queue.shift();
			// use current event goto-link as the option for the
			// next automatic redirect
			this.options.event_goto_link = ev.event_goto_link;
			return ev;
		} else {
			return false;
		}
	},
	
	getLabelType : function(unit) {
		var self = this;
		var label_field = self[unit + '_label_field'];
		if(!label_field) {
			return 'none';
		}
		if(label_field.hasClass('type-label')) {
			return 'label';
		}
		if(label_field.hasClass('type-separator')) {
			return 'separator';
		}
		return 'none';
	},
	
	getValues : function(diff) {
		this.diff = diff;
		this.mode = this.diff <= 0 ? 'up' : 'down';
		this.delta = this.mode == 'down' ? -1 : 1;
		
		if (this.diff <= 0) {
			// countup mode
			this.diff = this.diff * -1;
			this.text = this.text_up;
		} else {
			// countdown mode
			this.text = this.text_down;
		}
		
		// set values to display
		this.display_days = Math.floor(this.diff / (24 * 3600));
		this.display_hours = Math.floor((this.diff - this.display_days * 24 * 3600) / 3600);
		this.display_minutes = Math.floor((this.diff - this.display_days * 24
				* 3600 - this.display_hours * 3600) / 60);
		this.display_seconds = this.diff % 60;

		// check if we have to left-pad days with zeros
		var padded_days = '' + this.display_days;
		var left_pad_days = this.options.left_pad_days || 0;
		if(padded_days.length < left_pad_days) {
			// fast left-padding, max. 6 digits
			padded_days = ("000000" + this.display_days).slice(left_pad_days * -1); 
		}

		var digitsNew = '' + padded_days + 
			(this.display_hours < 10 ? "0" + this.display_hours : this.display_hours) + 
			(this.display_minutes < 10 ? "0" + this.display_minutes : this.display_minutes) +
			(this.display_seconds < 10 ? "0" + this.display_seconds : this.display_seconds);
		digitsNew = digitsNew.split('').reverse();

		return digitsNew;
	},
	
	go : function() {
		var current = new Date().getTime();
		// this value tends to be very close to 0 on normal run
		var correction = current - this.awake_detect - 1000;
		// save awake_detect right now to keep it very close
		// to current time
		this.awake_detect = current;
		// correction flag
		var has_correction = false;
		// ignore corrections < 50 to avoid accumulating
		// correction value in busy multithread environment
		// and/or instable timer on some computers
		if(correction > 50) {
			has_correction = true;
			// proceed with correction
			var new_diff = this.diff + this.delta * Math.floor(correction / 1000);
			this.awake_correction += correction % 1000;
			if(this.awake_correction >= 1000) {
				// apply correction if exceeds 1 second
				this.awake_correction -= 1000;
				new_diff += this.delta;
			}
			if(new_diff < 0 || (this.mode == 'up' && new_diff > this.up_limit)) {
				// if reached while suspended
				this.deadline_reached(new_diff);
				return;
			}			
			if(this.diff != new_diff) {
				// reinit counter if diff changed
				var newDigits = this.getValues(this.mode == 'up' ? -1 * new_diff : new_diff);
				this.digits.initialize(newDigits, {mode: this.mode});
			}
		}
		// run normally
		this.diff += this.delta;
		
		if(this.diff < 0 || (this.mode == 'up' && this.diff > this.up_limit)) {
			if(this.deadline_reached(this.diff)) {
				return;
			}
		}
		
		this.display_seconds += this.delta;
		if(this.delta < 0) {
			if (this.display_seconds < 0) {
				this.display_seconds = 59;
				this.display_minutes--;
			}
			if (this.display_minutes < 0) {
				this.display_minutes = 59;
				this.display_hours--;
			}
			if (this.display_hours < 0) {
				this.display_hours = 23;
				this.display_days--;
			}
		} else {
			if (this.display_seconds > 59) {
				this.display_seconds = 0;
				this.display_minutes++;
			}
			if (this.display_minutes > 59) {
				this.display_minutes = 0;
				this.display_hours++;
			}
			if (this.display_hours > 23) {
				this.display_hours = 0;
				this.display_days++;
			}
		}
		
		// check if we have to left-pad days with zeros
		var padded_days = '' + this.display_days;
		var left_pad_days = this.options.left_pad_days || 0;
		if(padded_days.length < left_pad_days) {
			// fast left-padding, max. 6 digits
			padded_days = ("000000" + this.display_days).slice(left_pad_days * -1); 
		}
		
		var digitsNew = '' + padded_days + 
			(this.display_hours < 10 ? "0" + this.display_hours : this.display_hours) + 
			(this.display_minutes < 10 ? "0" + this.display_minutes : this.display_minutes) +
			(this.display_seconds < 10 ? "0" + this.display_seconds : this.display_seconds);
		digitsNew = digitsNew.split('').reverse();
		
		this.digits.start(digitsNew, false);
			
		this.displayTexts(has_correction);
	},
	
	separator_state : false,
	
	displayTexts : function(adjustLabels) {
		// Set event text
		if(this.is_text_dirty) {
			// update text_field html only when needed
			this.text_field.set("html", this.text);
			this.is_text_dirty = false;
			
			// new option: make the counter box clickable
			if(this.options.counter_clickable != 0) {
				var first_link = this.container_field.getElement('a');
				// get the counter wrapper
				var counter_wrapper = this.container_field.getElement('.action-counter');
				// clean-up
				counter_wrapper.removeEvents('click');
				counter_wrapper.setStyle('cursor', '');
				
				// set href for redirect
				var href = false;
				if(first_link) {
					href = first_link.get('href');
				}
				if(this.options.event_goto_link) {
					href = this.options.event_goto_link;
				}
				// only add event if href exists
				if(href) {
					// set cursor
					counter_wrapper.setStyle('cursor', 'pointer');
					// add click event
					counter_wrapper.addEvent('click', function(event){
						window.location = href;
					});
				}
			}
		}
		
		this.displaySeparator('days');
		this.displaySeparator('hours');
		this.displaySeparator('minutes');
		this.displaySeparator('seconds');
		
		this.separator_state = !this.separator_state;
		
		// set counter usints visibility
		var i, assets = ['days', 'hours', 'minutes'], 
			values_sum = 0, // values sum on each iteration (to handle hide_zero_fields option)
			compact_view_unit = false; // active compact view unit, will never be set if compact_view option is not checked
		for(i = 0; i < assets.length; i++) {
			values_sum += this['display_' + assets[i]];
			if(this.options.compact_view == 1 && !compact_view_unit && this['display_' + assets[i]] > 0) {
				// for compact view, set compact_view_unit to first non-zero unit (staring from the highest one)
				compact_view_unit = assets[i];
			}
			// Check if any of the conditions to hide unit is true:
			// 		1. all higher unit values are zero and hide_zero_fields option is set
			//		2. compact_view option in set and current unit is not the same as compact_view_unit
			if((values_sum == 0 && this.options.hide_zero_fields != 0) || (this.options.compact_view == 1 && compact_view_unit != assets[i])) {
				this[assets[i] + '_field'].hide();
			} else { // otherwise show
				this[assets[i] + '_field'].show();
			}
		}
		// seconds special case
		if((this.options.display_seconds == 1 || values_sum == 0) && !compact_view_unit) {
			this.seconds_field.show();
		} else {
			this.seconds_field.hide();
		}

		if(adjustLabels || this.display_seconds == 59 || this.display_seconds == 0) {
			this.digits.adjustLabelsPos();
		}
	},
	
	displaySeparator : function(unit) {
		var self = this;
		var type = self[unit + '_label_type'];
		if(type == 'label') {
			// Set labels single/plural
			// function mod_scdpro_plural() is language-specific
			// and must be added to document by module's php script
			self[unit + '_label_field'].set('text', this.options.strings['MOD_SMARTCDPRO_N_' + unit.toUpperCase() + mod_scdpro_plural(self['display_' + unit])]);
		} else if(type == 'separator' && self.options['blinking_separator'] > 0) {
			self[unit + '_label_field'].setStyle('visibility', self.separator_state ? 'visible' : 'hidden');
		}
	},
	
	deadline_reached : function(new_diff) {
		// mark event text field for update
		this.is_text_dirty = true;
		
		var ev, interval, result = true, zero_crossed = new_diff < 0;
		var overflow = (zero_crossed ?
				(new_diff + 1) * -1 : // countdown zero crossed
				new_diff - 1) // count up limit crossed
				
				// exclude the event-causing-overflow up_limit
				- this.up_limit;
		
		if(zero_crossed && overflow < 0) {
			// this event allows count up and up_limit is not exceeded
			this.digits.initialize(this.getValues(new_diff), { mode: 'up' });
			this.displayTexts(true);
			// the mode has changed - trigger DOM observers for this module instance
			scdpCounters.triggerObservers(this.ids_suffix);
		} else {
			// Have to fetch next event
			while((ev = this.nextEvent()) !== false) {
				// next event's interval
				interval = ev.diff_js + ev.up_limit;
				
				if(overflow >= interval) {
					// skip this event, adjusting the overflow
					overflow -= interval;
				} else {
					// only a part of event's interval has passed
					this.mode = overflow < ev.diff_js ? 'down' : 'up';
					this.diff = ev.diff_js - overflow;
					this.up_limit = ev.up_limit;
					this.text_down = ev.text_down;
					this.text_up = ev.text_up;
					break;
				}
			}
			if(ev) {
				// new event correction (- 1) is OK for both down and up mode
				this.digits.initialize(this.getValues(this.diff - 1), { mode : this.mode });
				// a new event from queue: adjust labels vertical positions, as the new value
				// may differ in the number of displayed time units
				this.displayTexts(true);
				// this is a new event - trigger DOM observers for this module instance
				scdpCounters.triggerObservers(this.ids_suffix);
				
			} else {
				this.container_field.hide();
			}
		}
		
		if(zero_crossed && this.options.event_goto_link && this.options.event_goto != 0) {
			// goto url if set in options
			var confirmed;
			if(this.options.confirm_redirect == 1) {
				confirmed = confirm(this.options.strings.MOD_SMARTCDPRO_REDIRECT_CONFIRM_HINT);
			} else {
				confirmed = true;
			}
			if(confirmed) {
				// Check for special selector string in event_goto_link
				var selector_type = this.options.event_goto_link.substring(0, 1), form = null;
				if(selector_type == '#') {
					// id selector
					form = document.id(this.options.event_goto_link.substring(1));
				} else if(selector_type == '.') {
					// class selector
					form = $$(this.options.event_goto_link.substring(1)).pop();
				} else {
					form = null;
					// Normal flow - we have an URL
					var reset_query = '';
					if(this.options.session_time_count > 0) {
						// for redirect on zero crossed we add a special query var to redirect URL if session_time_count is set in options
						reset_query = (this.options.event_goto_link.indexOf('?') == -1 ? '?' : '&') + 'scdp_session_reset=' + this.ids_suffix;
					}
					window.location = this.options.event_goto_link + reset_query;
				}
				
				// submitting a form may last more than a second, a next timer tick can
				// happen while the page is not reloaded yet, thus creating infinite
				// "sumbit/load" loop. Use global flag to be sure that form submission
				// is done only once
				if(form && typeof(sc_global_form_submitted) === "undefined") {
					sc_global_form_submitted = true;
					// form is found, submit
					form.submit();
				}
			}
		}
		
		return result;
	}
});
