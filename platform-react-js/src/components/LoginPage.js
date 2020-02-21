
import React from 'react';

import {userLoginAttempt} from "../actions/actions";
import {renderField} from "../form";
import {reduxForm, Field} from 'redux-form';
import {connect} from 'react-redux';

const mapDispatchToProps = {
    userLoginAttempt
}
 class LoginPage extends React.Component
{
    onSubmit(values) {
        console.log(values)
    }
    render () {
        const {handleSubmit} = this.pops;

        return  (
            <div className="text-center">
                <form className="mt-4" onSubmit={handleSubmit(this.onSubmit.bind(this))}>
                    <div className="mt-4 form-group">
                        <Field name="usename" label="Username" type="text"  component={renderField}/>
                        <Field name="password" label="Password" type="password" component={renderField} />
                        <button type="submit" className="btn btn-primary btn-big btn-block">Log in</button>
                    </div>
                </form>
            </div>)
    }

}


export default reduxForm({
    form : 'LoginPage'
})(connect(null, mapDispatchToProps)(LoginPage));