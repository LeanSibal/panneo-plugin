var custom_range_sum = {
    left : null,
    right: null,
    leftValues: [],
    rightValues: [],
    init: function(){
        var _left = jQuery( '#custom_range_sum' ).data( 'left' );
        var _right = jQuery( '#custom_range_sum' ).data( 'right' );
        if( _left == null || _right == null ) return;
        this.left = jQuery( '#' + _left );
        this.right = jQuery( '#' + _right );
        this.leftValues = jQuery( this.left ).data( 'values' ).split(',');
        this.rightValues = jQuery( this.right ).data( 'values' ).split(',');
        this.compute();
        this.left.on( 'change', this.compute.bind( this ) );
        this.right.on( 'change', this.compute.bind( this ) );
    },
    compute: function(){
        var leftIndex = this.left.val();
        var rightIndex = this.right.val();
        if( typeof this.leftValues[ leftIndex ] === 'undefined' || typeof this.rightValues[ rightIndex ] == 'undefined' ) return false;
        var _sum = parseInt( this.leftValues[ leftIndex ] ) + parseInt( this.rightValues[ rightIndex ] );
        jQuery( '#custom_range_sum' ).text( new Intl.NumberFormat('de-DE').format( _sum ) );
    }

};
jQuery( document ).ready( function() {
    if( jQuery( '#custom_range_sum' ).length > 0 ) {
        custom_range_sum.init();
    }
});
