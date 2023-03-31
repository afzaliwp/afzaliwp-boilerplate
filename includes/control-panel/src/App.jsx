import React from 'react';
import styles from './style.css';

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <h1 className={ styles.test }>test here</h1>
        <p>
          Edit <code>src/App.js</code> and save.
        </p>
        <a
          className="App-link"
          href="https://reactjs.org"
          target="_blank"
          rel="noopener noreferrer"
        >
          Learn React
        </a>
      </header>
    </div>
  );
}

export default App;
