const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const env = process.env.NODE_ENV;
const path = require( 'path' );

module.exports = {
	mode: env || 'development',
	entry: './ui/admin.js',
	output: {
		path: path.resolve( __dirname, 'dist' ),
		filename: 'admin.js',
	},
	module: {
		rules: [
			{
				enforce: 'pre',
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'eslint-loader',
			},
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env', '@babel/preset-react' ],
					},
				},
			},
			{
				test: /\.css$/i,
				use: [ 'style-loader', 'css-loader' ],
			},
		],
	},
	plugins: [ new DependencyExtractionWebpackPlugin() ],
};
