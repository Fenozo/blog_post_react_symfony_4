import React from "react";
import ReactDOM from "react-dom";
import {applyMiddleware, createStore} from "redux";
import history from './history';
import {ConnectedRouter} from "react-router-redux";
import {Provider} from "react-redux";
import App from "./components/App";
import {Route} from "react-router";
import reducer from "./reducer";
import thunkMiddleware from 'redux-thunk';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCoffee } from '@fortawesome/free-solid-svg-icons'

import 'bootstrap/dist/css/bootstrap.min.css';
import './style.css';
const store = createStore(reducer, applyMiddleware(thunkMiddleware));


ReactDOM.render((
    <Provider store={store}>
        <ConnectedRouter history={history}>
            <Route path="/" component={App} />
        </ConnectedRouter>
    </Provider>

), document.getElementById('root'));

