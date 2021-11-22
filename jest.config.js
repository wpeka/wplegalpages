module.exports = {
	testPathIgnorePatterns: ['/node_modules/'],
	moduleNameMapper: {
		".+\\.(css|styl|less|sass|scss)$": "<rootDir>/node_modules/jest-css-modules",
		".+\\.(jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2|mp4|webm|wav|mp3|m4a|aac|oga)$": "<rootDir>/__mocks__/fileMock.js"
	},
	"transform": {
		"^.+\\.[t|j]sx?$": "babel-jest"
	},
	setupFilesAfterEnv: ['<rootDir>/jest-setup.js'],
	globals: {
		window: {},
		
	},
	"automock": false,
  	"resetMocks": false,
}