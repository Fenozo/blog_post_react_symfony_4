import React from 'react';
import {reduxForm, Field} from 'redux-form';
import {renderField} from '../form';
import {connect} from 'react-redux';
import {userLoginAttempt} from "../actions/actions";

const test = 1;
const mapDispatchToProps = {
    userLoginAttempt,
    test
};

class LoginForm extends React.Component {

    constructor(props) {
        super();
        console.log(props);

    }

    onSubmit (values) {
        console.log(this.props)
        return this.props.userLoginAttempt(
            values.username,
            values.password
        );
    }
    render() {
        const {handleSubmit} = this.props;
        return (
            <div className="text-center">
                <form className="mt-4" onSubmit={handleSubmit(this.onSubmit.bind(this))}>
                    <div className="mt-4 form-group">
                        <Field name="username" label="Username" type="text" component={renderField} />
                        <Field name="password" label="Password" type="password" component={renderField} />
                        <button type="submit" className="btn btn-primary btn-big btn-block">Log in</button>
                    </div>
                </form>
            </div>
        )
    }
}

export default reduxForm({
    form: 'LoginForm'
})(connect(null, mapDispatchToProps)(LoginForm));
