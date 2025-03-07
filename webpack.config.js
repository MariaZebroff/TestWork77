import path from 'path';

export default {
  entry: {
    app: path.resolve(process.cwd(), 'src', 'app.js'),
    admin: path.resolve(process.cwd(), 'src', 'admin.js'),
  },
  output: {
    path: path.resolve(process.cwd(), 'build'),
    filename: '[name].js', // This will generate app.js and admin.js
  },
  module: {},
};
