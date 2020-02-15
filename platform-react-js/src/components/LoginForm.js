import React from 'react';
import {Field, reduxForm} from 'redux-form';
import {renderField} from '../form';
class LoginForm extends React.Component {
    render() {
        console.log(this.props);
        return (
            <div>
                <form className="" action="">
                    <div className="mt-4 form-group">
                        <Field name="usename" label="Username" type="text" component={renderField} />
                        <Field name="password" label="Password" type="password" component={renderField} />
                        <button type="submit" className="btn btn-primary btn-big btn-block">Log in</button>
                    </div>
                </form>
            </div>
        )
    }
}

export default reduxForm({form: 'LoginForm'})(LoginForm);
