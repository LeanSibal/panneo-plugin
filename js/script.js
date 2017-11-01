var custom_range_sum = {
    left : null,
    right: null,
    leftValues: [],
    rightValues: [],
    leftSnap: null,
    rightSnap: null,
    init: function(){
        var _left = jQuery( '#custom_range_sum' ).data( 'left' );
        var _right = jQuery( '#custom_range_sum' ).data( 'right' );
        if( _left == null || _right == null ) return;
        this.left = jQuery( '#' + _left );
        this.right = jQuery( '#' + _right );
        this.leftValues = jQuery( this.left ).data( 'values' ).split(',');
        this.rightValues = jQuery( this.right ).data( 'values' ).split(',');
        if( typeof jQuery( this.left ).data('snap') !== 'undefined' ) {
            this.leftSnap = jQuery( this.left ).data( 'snap' ).split(',');
        }
        if( typeof jQuery( this.right ).data('snap') !== 'undefined' ) {
            this.rightSnap = jQuery( this.right ).data( 'snap' ).split(',');
        }
        this.compute();
        this.left.on( 'change', this.compute.bind( this ) );
        this.right.on( 'change', this.compute.bind( this ) );
    },
    compute: function(){
        var leftCount = this.leftValues.length - 1,
            rightCount = this.rightValues.length - 1;
        var leftIndex = this.getComputedIndex( Math.round( this.left.val() ), leftCount, this.leftSnap ),
            rightIndex = this.getComputedIndex( Math.round( this.right.val() ), rightCount, this.rightSnap );
        this.moveSlider( this.left, leftIndex, this.leftSnap);
        this.moveSlider( this.right, rightIndex, this.rightSnap );

        if( typeof this.leftValues[ leftIndex ] === 'undefined' || typeof this.rightValues[ rightIndex ] == 'undefined' ) return false;
        var _sum = parseInt( this.leftValues[ leftIndex ] ) + parseInt( this.rightValues[ rightIndex ] );
        jQuery( '#custom_range_sum' ).text( new Intl.NumberFormat('de-DE').format( _sum ) );
    },
    moveSlider: function( slider, index, snap ) {
        var step = slider.data( 'values' ).split(',').length - 1;
        var from = Math.round( slider.val() );
        var to = index * ( 100 / step );
        if( snap != null && typeof snap[ index ] !== 'undefined' ) {
            var to = snap[ index ];
        }
        var difference = 0;
        if( from > to ) {
            difference = from - to;
        } else if ( from < to ) {
            difference = to - from;
        }
        var steps = 100 / difference;
        for( var i = 0; i < difference; i++ ) {
            setTimeout( this.setSliderValue.bind( this, slider, from, to, i ), steps * i );
        }
        setTimeout( this.setSliderValue.bind( this, slider, from, to, i ), 200 );
    },
    setSliderValue: function( element, from, to, index) {
        if( from > to ) {
            element.val( from - index );
        } else {
            element.val( from + index );
        }
    },
    getComputedIndex: function( index, size, snap ) {
        var step = ( 100 / ( size + 1 ) );
        for( var i = 0; i < size; i++ ) {
            var lowerThreshold = ( step * i ) - ( step / 2 );
            var upperThreshold = ( step * i ) + ( step / 2 );
            if( snap !== null && i == 0 ) {
                var center = parseInt( snap[i] );
                var upperNumber = parseInt( snap[i + 1] );
                lowerThreshold = -1;
                upperThreshold = center + ( (  upperNumber - center ) / 2 );
            } else if( snap !== null && typeof snap[i] !== 'undefined' && typeof snap[i - 1] !== 'undefined' && typeof snap[i + 1] !== 'undefined' ) {
                var lowerNumber = parseInt( snap[i - 1] );
                var center = parseInt( snap[i] );
                var upperNumber = parseInt( snap[i + 1] );
                lowerThreshold = center - ( ( center - lowerNumber ) / 2 );
                upperThreshold = center + ( (  upperNumber - center ) / 2 );
            }
            if( index > lowerThreshold && index < upperThreshold ) {
                return i;
            }
        }
        return size;
    }

};
jQuery( document ).ready( function() {
    if( jQuery( '#custom_range_sum' ).length > 0 ) {
        custom_range_sum.init();
    }
});
