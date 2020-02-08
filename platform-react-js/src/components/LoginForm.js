import React from 'react';

class LoginForm extends React.Component {
    render() {
        return (
            <div>
                <form action="">
                    <div className="form-group">
                        <input type="text" className="form-control" name="" />
                    </div>

                    <div className="form-group">
                        <input type="text" className="form-control" name="" />
                    </div>

                    <button className="btn btn-info"> Se connecter </button>
                </form>
            </div>
        )
    }
}

export default LoginForm;
