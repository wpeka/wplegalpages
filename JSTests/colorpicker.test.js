/**
 * @jest-environment jsdom
 */
import colorpicker from '../src/colorpicker';

it( 'Test for template', () => {
	var retrievedTemplate = colorpicker.template;
    expect(typeof retrievedTemplate).toBe('string');
    var htmlEscaped = retrievedTemplate.replace(/<\/?[^>]+(>|$)/g, "");
    expect( htmlEscaped === retrievedTemplate ).toBeFalsy();
})

it('test for data', ()=>{
    var expectedData = {
		colors: {
			hex: '#000000',
		},
		colorValue: '',
		displayPicker: false,
	}
    var retrievedData = colorpicker.data();
    expect(expectedData).toEqual(retrievedData);
})

it('test for methods', () => {
	var module = {
		colorValue: '',
		colors: {
			hex: '#000000'
		},
		updateColors(color) {
			this.colors = {
				hex: color
			}
		}
	}
	var settingColor = colorpicker.methods.setColor.bind(module);
	settingColor( '#123123' );
	expect(module.colorValue).toBe('#123123');

	var updatedColors = colorpicker.methods.updateColors.bind(module);
	updatedColors( 'rgba(100,100,100,0.5)' );
	expect(module.colors).toEqual( {
		hex: '#646464',
		a: '0.5'
	} )
	updatedColors('#121212');
	expect(module.colors).toEqual({
		hex: '#121212'
	})
})