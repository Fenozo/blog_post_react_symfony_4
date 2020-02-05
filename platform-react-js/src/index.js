import React from "react";
import ReactDOM from "react-dom";
import {createStore} from "redux";
import createHistory from "history/createBrowserHistory";
import {ConnectedRouter} from "react-router-redux";
import {Route} from "react-router";
import {Provider} from "react-redux";
import App from "./components/App";
import LoginForm from "./components/LoginForm";
import {Switch} from "react-router-dom";

const store = createStore(
    state => state
);
const history = createHistory();

ReactDOM.render((
    <Provider store={store}>
        <ConnectedRouter history={history}>
            <Switch>
                <Route path="/login" component={LoginForm} />
                <Route path="/" component={App} />
            </Switch>
        </ConnectedRouter>
    </Provider>

), document.getElementById('root'));

